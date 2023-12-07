<?php

namespace App\Controller;

use App\Entity\Chambres;
use App\Entity\ChambresReservees;
use App\Entity\Reservations;
use App\Repository\ChambresReserveesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ChambresReserveesController extends AbstractController
{
    #[Route('/chambres_reservees', name: 'app_chambres_reservees', methods: ['GET'])]
    public function getAllChambresReservees(ChambresReserveesRepository $chambresReserveesRepository, SerializerInterface $serializer): JsonResponse
    {
        try {
            $chambresReserveesList = $chambresReserveesRepository->findAll();
            $jsonChambresReserveesList = $serializer->serialize($chambresReserveesList, 'json');

            return new JsonResponse($jsonChambresReserveesList, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(
                ['message' => 'Une erreur est survenue lors de la récupération des chambres réservées.'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/chambres_reservees/{id}', name: 'detailChambreReservee', methods: ['GET'])]
    public function getDetailChambreReservee(ChambresReservees $chambreReservee = null, SerializerInterface $serializer)
    {
        if (!$chambreReservee) {
            return new JsonResponse(['error' => 'Chambre réservée introuvable'], Response::HTTP_NOT_FOUND);
        }

        try {
            $jsonChambreReservee = $serializer->serialize($chambreReservee, 'json');
            return new JsonResponse($jsonChambreReservee, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Une erreur est survenue lors de la sérialisation'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/chambres_reservees/{id}', name: 'deleteChambreReservee', methods: ['DELETE'])]
    public function deleteChambreReservee(ChambresReservees $chambreReservee, EntityManagerInterface $em): JsonResponse 
    {
        if (!$chambreReservee) {
            return new JsonResponse(['message' => 'Chambre réservée non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        try {
            $em->remove($chambreReservee);
            $em->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Erreur lors de la suppression de la chambre réservée.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'La chambre réservée a été supprimée avec succès.'], Response::HTTP_NO_CONTENT);
    }
    
    
    #[Route('/chambres_reservees', name: 'createChambreReservee', methods: ['POST'])]
    public function createChambreReservee(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);

        if (!isset($data['reservation_id']) || !isset($data['chambre_id'])) {
            return new JsonResponse(['message' => 'Toutes les informations requises doivent être fournies.'], Response::HTTP_BAD_REQUEST);
        }

        $reservation = $entityManager->getRepository(Reservations::class)->find($data['reservation_id']);
        $chambre = $entityManager->getRepository(Chambres::class)->find($data['chambre_id']);

        if (!$reservation || !$chambre) {
            return new JsonResponse(['message' => 'Réservation ou chambre non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $chambreReservee = new ChambresReservees();
        $chambreReservee->setReservation($reservation);
        $chambreReservee->setChambre($chambre);

        try {
            $entityManager->persist($chambreReservee);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Une erreur est survenue lors de la création de la chambre réservée.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'La chambre réservée a été créée avec succès.'], Response::HTTP_CREATED);
    }

    #[Route('/chambres_reservees/{id}', name: 'updateChambreReservee', methods: ['PUT'])]
    public function updateChambreReservee(ChambresReservees $chambreReservee, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$chambreReservee) {
            return new JsonResponse(['message' => 'Chambre réservée non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);

        if (!isset($data['reservation_id']) || !isset($data['chambre_id'])) {
            return new JsonResponse(['message' => 'Toutes les informations requises doivent être fournies.'], Response::HTTP_BAD_REQUEST);
        }

        $reservation = $entityManager->getRepository(Reservations::class)->find($data['reservation_id']);
        $chambre = $entityManager->getRepository(Chambres::class)->find($data['chambre_id']);

        if (!$reservation || !$chambre) {
            return new JsonResponse(['message' => 'Réservation ou chambre non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $chambreReservee->setReservation($reservation);
        $chambreReservee->setChambre($chambre);

        try {
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Une erreur est survenue lors de la mise à jour de la chambre réservée.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'La chambre réservée a été mise à jour avec succès.'], Response::HTTP_OK);
    }
}
