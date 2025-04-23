<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\service\ReservationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\LogementRepository;

#[Route('/reservation')]
final class ReservationController extends AbstractController
{
    #[Route(name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/api/findAll', name: 'apiJson3', methods: ['GET'])]
    public function api(Reservationservice $Reservationservice)
    {
        return new JsonResponse($Reservationservice->getAllReservation(),Response::HTTP_OK);
    }

    #[Route('/api/add', name: 'api_add_reservation', methods: ['POST'])]
    public function addReservation(Request $request, EntityManagerInterface $entityManager, \App\Repository\LogementRepository $logementRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        if (
            !$data || 
            !isset($data['Nom_voyageur'], $data['date_debut'], $data['date_fin'], $data['prix'], $data['logement'], $data['plateforme'])
        ) {
            return new JsonResponse(['error' => 'Données incomplètes ou invalides'], Response::HTTP_BAD_REQUEST);
        }
    
        $reservation = new Reservation();
        $reservation->setNomVoyageur($data['Nom_voyageur']);
        $reservation->setPlateforme($data['plateforme']); // ✅
    
        try {
            $reservation->setDate(new \DateTime($data['date_debut']));
            $reservation->setDateFin(new \DateTime($data['date_fin']));
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Format de date invalide'], Response::HTTP_BAD_REQUEST);
        }
    
        $reservation->setPrix($data['prix']);
    
        $logement = $logementRepository->find($data['logement']);
        if (!$logement) {
            return new JsonResponse(['error' => 'Logement non trouvé'], Response::HTTP_NOT_FOUND);
        }
    
        $reservation->setLogement($logement);
    
        $entityManager->persist($reservation);
        $entityManager->flush();
    
        return new JsonResponse(['message' => 'Réservation ajoutée avec succès'], Response::HTTP_CREATED);
    }
    

    #[Route('/api/update/{id}', name: 'api_reservation_update', methods: ['PUT'])]
    public function updateReservation(
        Request $request,
        Reservation $reservation,
        EntityManagerInterface $em,
        LogementRepository $logementRepo
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
    
        if (!isset($data['Nom_voyageur'], $data['date'], $data['date_fin'], $data['prix'], $data['logement'], $data['plateforme'])) {
            return new JsonResponse(['error' => 'Données incomplètes'], Response::HTTP_BAD_REQUEST);
        }
    
        $reservation->setNomVoyageur($data['Nom_voyageur']);
        $reservation->setPlateforme($data['plateforme']); // ✅
        $reservation->setDate(new \DateTime($data['date']));
        $reservation->setDateFin(new \DateTime($data['date_fin']));
        $reservation->setPrix($data['prix']);
    
        $logement = $logementRepo->find($data['logement']);
        if (!$logement) {
            return new JsonResponse(['error' => 'Logement introuvable'], Response::HTTP_NOT_FOUND);
        }
    
        $reservation->setLogement($logement);
        $em->flush();
    
        return new JsonResponse(['message' => 'Réservation mise à jour']);
    }
    




#[Route('/api/delete/{id}', name: 'api_reservation_delete', methods: ['DELETE'])]
public function deleteReservation(
    Reservation $reservation,
    EntityManagerInterface $em
): JsonResponse {
    $em->remove($reservation);
    $em->flush();

    return new JsonResponse(['message' => 'Réservation supprimée']);
}

}
