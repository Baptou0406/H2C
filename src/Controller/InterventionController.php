<?php

namespace App\Controller;

use App\Entity\Intervention;
use App\Form\InterventionType;
use App\Repository\InterventionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\service\InterventionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\LogementRepository;


#[Route('/intervention')]
final class InterventionController extends AbstractController
{
    #[Route(name: 'app_intervention_index', methods: ['GET'])]
    public function index(InterventionRepository $interventionRepository): Response
    {
        return $this->render('intervention/index.html.twig', [
            'interventions' => $interventionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_intervention_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $intervention = new Intervention();
        $form = $this->createForm(InterventionType::class, $intervention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($intervention);
            $entityManager->flush();

            return $this->redirectToRoute('app_intervention_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('intervention/new.html.twig', [
            'intervention' => $intervention,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_intervention_show', methods: ['GET'])]
    public function show(Intervention $intervention): Response
    {
        return $this->render('intervention/show.html.twig', [
            'intervention' => $intervention,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_intervention_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Intervention $intervention, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InterventionType::class, $intervention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_intervention_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('intervention/edit.html.twig', [
            'intervention' => $intervention,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_intervention_delete', methods: ['POST'])]
    public function delete(Request $request, Intervention $intervention, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$intervention->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($intervention);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_intervention_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/api/findAll', name: 'apiJson4', methods: ['GET'])]
    public function api(Interventionservice $Interventionservice)
    {
        return new JsonResponse($Interventionservice->getAllIntervention(),Response::HTTP_OK);
    }

    #[Route('/api/add', name: 'api_add_intervention', methods: ['POST'])]
    public function addIntervention(
        Request $request,
        EntityManagerInterface $entityManager,
        \App\Repository\LogementRepository $logementRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (
            !$data ||
            !isset($data['Description'], $data['prix'], $data['logement'])
        ) {
            return new JsonResponse(['error' => 'Données manquantes'], Response::HTTP_BAD_REQUEST);
        }

        $intervention = new \App\Entity\Intervention();
        $intervention->setDescription($data['Description']);
        $intervention->setPrix($data['prix']);

        // Gestion de la date
        if (isset($data['date']) && !empty($data['date'])) {
            try {
                $date = new \DateTime($data['date']);
                $intervention->setDate($date);
            } catch (\Exception $e) {
                // Si la date est invalide, on utilise la date actuelle
                $intervention->setDate(new \DateTime());
            }
        } else {
            // Par défaut, on utilise la date actuelle
            $intervention->setDate(new \DateTime());
        }

        $logement = $logementRepository->find($data['logement']);
        if (!$logement) {
            return new JsonResponse(['error' => 'Logement non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $intervention->setLogement($logement);

        $entityManager->persist($intervention);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Intervention ajoutée avec succès'], Response::HTTP_CREATED);
    }

    #[Route('/api/update/{id}', name: 'api_intervention_update', methods: ['PUT'])]
    public function updateIntervention(
        Request $request,
        Intervention $intervention,
        EntityManagerInterface $em,
        LogementRepository $logementRepo
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['Description'], $data['prix'], $data['logement'])) {
            return new JsonResponse(['error' => 'Données incomplètes'], Response::HTTP_BAD_REQUEST);
        }

        $intervention->setDescription($data['Description']);
        $intervention->setPrix($data['prix']);

        // Gestion de la date
        if (isset($data['date']) && !empty($data['date'])) {
            try {
                $date = new \DateTime($data['date']);
                $intervention->setDate($date);
            } catch (\Exception $e) {
                // Si la date est invalide, on ne change pas la date existante
            }
        }

        $logement = $logementRepo->find($data['logement']);
        if (!$logement) {
            return new JsonResponse(['error' => 'Logement introuvable'], Response::HTTP_NOT_FOUND);
        }

        $intervention->setLogement($logement);

        $em->flush();

        return new JsonResponse(['message' => 'Intervention modifiée']);
    }

    #[Route('/api/delete/{id}', name: 'api_intervention_delete', methods: ['DELETE'])]
    public function deleteIntervention(
        Intervention $intervention,
        EntityManagerInterface $em
    ): JsonResponse {
        $em->remove($intervention);
        $em->flush();

        return new JsonResponse(['message' => 'Intervention supprimée avec succès']);
    }
}