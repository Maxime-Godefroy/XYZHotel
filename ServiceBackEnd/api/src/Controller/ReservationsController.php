<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Chambres;
use App\Entity\ChambresReservees;
use App\Entity\ComptesClients;
use App\Entity\Reservations;
use App\Repository\ReservationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ReservationsController extends AbstractController
{
    #[Route('/reservations', name: 'app_reservations', methods: ['GET'])]
    public function getAllReservations(ReservationsRepository $reservationsRepository, SerializerInterface $serializer): JsonResponse
    {
        try {
            $reservationsList = $reservationsRepository->findAll();
            $jsonReservationsList = $serializer->serialize($reservationsList, 'json');

            return new JsonResponse($jsonReservationsList, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(
                ['message' => 'Une erreur est survenue lors de la récupération des réservations.'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/reservations/{id}', name: 'detailReservation', methods: ['GET'])]
    public function getDetailReservation(Reservations $reservation = null, SerializerInterface $serializer)
    {
        if (!$reservation) {
            return new JsonResponse(['error' => 'Réservation introuvable'], Response::HTTP_NOT_FOUND);
        }

        try {
            $jsonReservation = $serializer->serialize($reservation, 'json');
            return new JsonResponse($jsonReservation, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Une erreur est survenue lors de la sérialisation'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/reservations/{id}', name: 'deleteReservation', methods: ['DELETE'])]
    public function deleteReservation(Reservations $reservation, EntityManagerInterface $em): JsonResponse 
    {
        if (!$reservation) {
            return new JsonResponse(['message' => 'Réservation non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        try {
            $em->remove($reservation);
            $em->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Erreur lors de la suppression de la réservation.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'La réservation a été supprimée avec succès.'], Response::HTTP_NO_CONTENT);
    }
    
    
    #[Route('/reservations', name: 'createReservation', methods: ['POST'])]
    public function createReservation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);

        if (!isset($data['client_id']) || !isset($data['date_checkin']) || !isset($data['nombre_nuits']) || !isset($data['statut'])) {
            return new JsonResponse(['message' => 'Toutes les informations requises doivent être fournies.'], Response::HTTP_BAD_REQUEST);
        }

        $client = $entityManager->getRepository(Clients::class)->find($data['client_id']);

        if (!$client) {
            return new JsonResponse(['message' => 'Client non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $reservation = new Reservations();
        $reservation->setClient($client);
        $reservation->setDateCheckin(new \DateTime($data['date_checkin']));
        $reservation->setNombreNuits($data['nombre_nuits']);
        $reservation->setStatut($data['statut']);

        try {
            $entityManager->persist($reservation);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Une erreur est survenue lors de la création de la réservation.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'La réservation a été créée avec succès.'], Response::HTTP_CREATED);
    }

    #[Route('/reservations/{id}', name: 'updateReservation', methods: ['PUT'])]
    public function updateReservation(Reservations $reservation, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$reservation) {
            return new JsonResponse(['message' => 'Réservation non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);

        if (!isset($data['client_id']) || !isset($data['date_checkin']) || !isset($data['nombre_nuits']) || !isset($data['statut'])) {
            return new JsonResponse(['message' => 'Toutes les informations requises doivent être fournies.'], Response::HTTP_BAD_REQUEST);
        }

        $client = $entityManager->getRepository(Clients::class)->find($data['client_id']);

        if (!$client) {
            return new JsonResponse(['message' => 'Client non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $reservation->setClient($client);
        $reservation->setDateCheckin(new \DateTime($data['date_checkin']));
        $reservation->setNombreNuits($data['nombre_nuits']);
        $reservation->setStatut($data['statut']);

        try {
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Une erreur est survenue lors de la mise à jour de la réservation.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'La réservation a été mise à jour avec succès.'], Response::HTTP_OK);
    }

    #[Route('/reservations_effectuer', name: 'effectuerReservation', methods: ['POST'])]
    public function effectuerReservation(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);

        if (!isset($data['client_id']) || !isset($data['date_checkin']) || !isset($data['nombre_nuits']) || !isset($data['chambres'])) {
            return new JsonResponse(['message' => 'Toutes les informations requises doivent être fournies.'], Response::HTTP_BAD_REQUEST);
        }

        $client = $entityManager->getRepository(Clients::class)->find($data['client_id']);

        if (!$client) {
            return new JsonResponse(['message' => 'Client non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $chambre = $entityManager->getRepository(Chambres::class)->find($data['chambres']);

        if (!$chambre) {
            return new JsonResponse(['message' => 'Chambre non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $prixChambre = $chambre->getPrixNuit();

        $montantTotal = $data['nombre_nuits'] * $prixChambre;

        $reservation = new Reservations();
        $reservation->setClient($client);
        $reservation->setDateCheckin(new \DateTime($data['date_checkin']));
        $reservation->setNombreNuits($data['nombre_nuits']);
        $reservation->setStatut('En attente');

        $chambreReservee = new ChambresReservees();
        $chambreReservee->setReservation($reservation);
        $chambreReservee->setChambre($chambre);

        try {
            $entityManager->persist($reservation);
            $entityManager->persist($chambreReservee);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Une erreur est survenue lors de la création de la réservation.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $montantDebit = $montantTotal * 0.5;

        $compteClient = $entityManager->getRepository(ComptesClients::class)->findOneBy(['client' => $client]);

        if (!$compteClient) {
            $entityManager->remove($reservation);
            $entityManager->remove($chambreReservee);
            $entityManager->flush();

            return new JsonResponse(['message' => 'Compte du client non trouvé. La réservation a été annulée.'], Response::HTTP_NOT_FOUND);
        }

        if ($compteClient->getSoldePortefeuille() < $montantDebit) {
            $entityManager->remove($reservation);
            $entityManager->remove($chambreReservee);
            $entityManager->flush();

            return new JsonResponse(['message' => 'Solde insuffisant pour le débit initial. La réservation a été annulée.'], Response::HTTP_BAD_REQUEST);
        }

        $compteClient->setSoldePortefeuille($compteClient->getSoldePortefeuille() - $montantDebit);

        try {
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Erreur lors de la mise à jour du solde.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'La réservation a été effectuée avec succès.'], Response::HTTP_CREATED);
    }

    #[Route('/reservations_confirmer/{id}', name: 'confirmerReservation', methods: ['PUT'])]
    public function confirmerReservation(Reservations $reservation, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$reservation) {
            return new JsonResponse(['message' => 'Réservation non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $chambresReserveesRepository = $entityManager->getRepository(ChambresReservees::class);
        $chambreReservee = $chambresReserveesRepository->findOneBy(['reservation' => $reservation]);

        if (!$chambreReservee) {
            return new JsonResponse(['message' => 'Chambre réservée non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $chambre = $chambreReservee->getChambre();

        if (!$chambre) {
            return new JsonResponse(['message' => 'Chambre non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $montantRestant = $reservation->getNombreNuits() * $chambre->getPrixNuit() * 0.5;

        $client = $reservation->getClient();

        $compteClient = $entityManager->getRepository(ComptesClients::class)->findOneBy(['client' => $client]);

        if (!$compteClient) {
            return new JsonResponse(['message' => 'Compte du client non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        if ($compteClient->getSoldePortefeuille() < $montantRestant) {
            return new JsonResponse(['message' => 'Solde insuffisant pour la confirmation de la réservation.'], Response::HTTP_BAD_REQUEST);
        }

        $compteClient->setSoldePortefeuille($compteClient->getSoldePortefeuille() - $montantRestant);
        $reservation->setStatut('Confirmée');

        try {
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Une erreur est survenue lors de la confirmation de la réservation.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'La réservation a été confirmée avec succès.'], Response::HTTP_OK);
    }

    #[Route('/reservations_annuler/{id}', name: 'annulerReservation', methods: ['DELETE'])]
    public function annulerReservation(Reservations $reservation, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$reservation) {
            return new JsonResponse(['message' => 'Réservation non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $client = $reservation->getClient();
        
        $chambresReserveesRepository = $entityManager->getRepository(ChambresReservees::class);
        $chambreReservee = $chambresReserveesRepository->findOneBy(['reservation' => $reservation]);

        if ($chambreReservee) {
            $chambreReservee->setReservation(null);
            $entityManager->remove($chambreReservee);
        }

        $prixChambre = $chambreReservee->getChambre()->getPrixNuit();
        $montantRemboursement = $reservation->getNombreNuits() * $prixChambre;

        if ($reservation->getStatut() === 'En attente' || $reservation->getStatut() === 'Confirmée') {
            $montantRemboursement = $reservation->getStatut() === 'En attente' ? $montantRemboursement * 0.5 : $montantRemboursement;

            $compteClient = $entityManager->getRepository(ComptesClients::class)->findOneBy(['client' => $client]);

            if ($compteClient) {
                $compteClient->setSoldePortefeuille($compteClient->getSoldePortefeuille() + $montantRemboursement);
                $entityManager->persist($compteClient);
            }
        }

        try {
            $entityManager->remove($reservation);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Une erreur est survenue lors de l\'annulation de la réservation.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'La réservation a été annulée avec succès.'], Response::HTTP_OK);
    }
}
