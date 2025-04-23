<?php

namespace App\Controller;

use App\Entity\Consommable;
use App\Form\ConsommableType;
use App\Repository\ConsommableRepository;
use App\Entity\Logement;
use App\Form\LogementType;
use App\Repository\LogementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\service\ConsommableService;

#[Route('/consommable')]
final class ConsommableController extends AbstractController
{
    #[Route(name: 'app_consommable_index', methods: ['GET'])]
    public function index(ConsommableRepository $consommableRepository): Response
    {
        return $this->render('consommable/index.html.twig', [
            'consommables' => $consommableRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_consommable_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $consommable = new Consommable();
        $form = $this->createForm(ConsommableType::class, $consommable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($consommable);
            $entityManager->flush();

            return $this->redirectToRoute('app_consommable_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('consommable/new.html.twig', [
            'consommable' => $consommable,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_consommable_show', methods: ['GET'])]
    public function show(Consommable $consommable): Response
    {
        return $this->render('consommable/show.html.twig', [
            'consommable' => $consommable,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_consommable_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Consommable $consommable, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConsommableType::class, $consommable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_consommable_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('consommable/edit.html.twig', [
            'consommable' => $consommable,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_consommable_delete', methods: ['POST'])]
    public function delete(Request $request, Consommable $consommable, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consommable->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($consommable);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_consommable_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/api/add', name: 'api_add_consommable', methods: ['POST'])]
    public function addConsommable(Request $request, EntityManagerInterface $entityManager, LogementRepository $logementRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['Nom magasin'], $data['prix'], $data['logement'])) {
            return new JsonResponse(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }

        $consommable = new Consommable();
        $consommable->setNomMagasin($data['Nom magasin']);
        $consommable->setPrix($data['prix']);

        // Gestion de la date
        if (isset($data['date']) && !empty($data['date'])) {
            try {
                $date = new \DateTime($data['date']);
                $consommable->setDate($date);
            } catch (\Exception $e) {
                // Si la date est invalide, on utilise la date actuelle
                $consommable->setDate(new \DateTime());
            }
        } else {
            // Par défaut, on utilise la date actuelle
            $consommable->setDate(new \DateTime());
        }

        $logement = $logementRepository->find($data['logement']);
        if (!$logement) {
            return new JsonResponse(['error' => 'Logement non trouvé'], Response::HTTP_NOT_FOUND);
        }
        
        $consommable->setLogement($logement);

        $entityManager->persist($consommable);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Consommable ajouté avec succès'], Response::HTTP_CREATED);
    }
    
    #[Route('/api/findAll', name: 'apiJson1', methods: ['GET'])]
    public function api(ConsommableService $ConsommableService)
    {
        return new JsonResponse($ConsommableService->getAllConsommable(),Response::HTTP_OK);
    }

    #[Route('/api/update/{id}', name: 'api_consommable_updatee', methods: ['PUT'])]
    public function editConsommable(
        Request $request,
        Consommable $consommable,
        EntityManagerInterface $em,
        LogementRepository $logementRepo
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['Nom magasin'], $data['prix'], $data['logement'])) {
            return new JsonResponse(['error' => 'Données incomplètes'], Response::HTTP_BAD_REQUEST);
        }

        $consommable->setNomMagasin($data['Nom magasin']);
        $consommable->setPrix($data['prix']);

        // Gestion de la date
        if (isset($data['date']) && !empty($data['date'])) {
            try {
                $date = new \DateTime($data['date']);
                $consommable->setDate($date);
            } catch (\Exception $e) {
                // Si la date est invalide, on ne modifie pas la date existante
            }
        }

        $logement = $logementRepo->find($data['logement']);
        if (!$logement) {
            return new JsonResponse(['error' => 'Logement introuvable'], Response::HTTP_NOT_FOUND);
        }

        $consommable->setLogement($logement);
        $em->flush();

        return new JsonResponse(['message' => 'Consommable modifié']);
    }
    
    #[Route('/api/delete/{id}', name: 'api_consommable_delete', methods: ['DELETE'])]
    public function deleteConsommable(
        Consommable $consommable,
        EntityManagerInterface $em
    ): JsonResponse {
        $em->remove($consommable);
        $em->flush();

        return new JsonResponse(['message' => 'Consommable supprimé']);
    }
}