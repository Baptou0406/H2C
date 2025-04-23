<?php

namespace App\Controller;

use App\Entity\Logement;
use App\Form\LogementType;
use App\Repository\LogementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\service\LogementService;

#[Route('/logement')]
final class LogementController extends AbstractController
{
    #[Route(name: 'app_logement_index', methods: ['GET'])]
    public function index(LogementRepository $logementRepository): Response
    {
        return $this->render('logement/index.html.twig', [
            'logements' => $logementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_logement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $logement = new Logement();
        $form = $this->createForm(LogementType::class, $logement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($logement);
            $entityManager->flush();

            return $this->redirectToRoute('app_logement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('logement/new.html.twig', [
            'logement' => $logement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_logement_show', methods: ['GET'])]
    public function show(Logement $logement): Response
    {
        return $this->render('logement/show.html.twig', [
            'logement' => $logement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_logement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Logement $logement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LogementType::class, $logement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_logement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('logement/edit.html.twig', [
            'logement' => $logement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_logement_delete', methods: ['POST'])]
    public function delete(Request $request, Logement $logement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$logement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($logement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_logement_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/api/findAll', name: 'apiJson2', methods: ['GET'])]
    public function api(Logementservice $Logementservice)
    {
        return new JsonResponse($Logementservice->getAllLogement(),Response::HTTP_OK);
    }

   #[Route('/api/add', name: 'api_add_logement', methods: ['POST'])]
public function addLogement(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    if (!$data || !isset($data['nom'])) {
        return new JsonResponse(['error' => 'Nom du logement manquant'], Response::HTTP_BAD_REQUEST);
    }

    $logement = new Logement();
    $logement->setNom($data['nom']);

    if (isset($data['commission'])) {
        $logement->setCommission((float)$data['commission']);
    }

    if (isset($data['menage'])) {
        $logement->setMenage((float)$data['menage']);
    }

    $entityManager->persist($logement);
    $entityManager->flush();

    return new JsonResponse(['message' => 'Logement ajouté avec succès'], Response::HTTP_CREATED);
}
#[Route('/api/update/{id}', name: 'api_logement_update', methods: ['PUT'])]
public function updateLogement(
    Request $request,
    Logement $logement,
    EntityManagerInterface $em
): JsonResponse {
    $data = json_decode($request->getContent(), true);

    if (!isset($data['Nom'], $data['Commission'], $data['Menage'])) {
        return new JsonResponse(['error' => 'Données incomplètes'], Response::HTTP_BAD_REQUEST);
    }

    $logement->setNom($data['Nom']);
    $logement->setCommission($data['Commission']);
    $logement->setMenage($data['Menage']);

    $em->flush();

    return new JsonResponse(['message' => 'Logement modifié']);
}

#[Route('/api/delete/{id}', name: 'api_logement_delete', methods: ['DELETE'])]
public function deleteLogement(
    Logement $logement,
    EntityManagerInterface $em
): JsonResponse {
    $em->remove($logement);
    $em->flush();

    return new JsonResponse(['message' => 'Logement supprimé']);
}


}
