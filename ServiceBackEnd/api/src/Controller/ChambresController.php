<?php

namespace App\Controller;

use App\Entity\Chambres;
use App\Repository\ChambresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ChambresController extends AbstractController
{
    #[Route('/{path}', name: 'app_chambres', requirements: ['path' => '(chambres)?'], methods: ['GET'])]
    public function getAllChambres(ChambresRepository $chambresRepository, SerializerInterface $serializer): JsonResponse
    {
        try {
            $chambresList = $chambresRepository->findAll();
            $jsonChambresList = $serializer->serialize($chambresList, 'json');

            return new JsonResponse($jsonChambresList, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(
                ['message' => 'Une erreur est survenue lors de la récupération des chambres.'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/chambres/{id}', name: 'detailChambre', methods: ['GET'])]
    public function getDetailChambre(Chambres $chambre = null, SerializerInterface $serializer)
    {
        if (!$chambre) {
            return new JsonResponse(['error' => 'Chambre introuvable'], Response::HTTP_NOT_FOUND);
        }

        try {
            $jsonChambre = $serializer->serialize($chambre, 'json');
            return new JsonResponse($jsonChambre, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Une erreur est survenue lors de la sérialisation'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/chambres/{id}', name: 'deleteChambre', methods: ['DELETE'])]
    public function deleteChambre(Chambres $chambre, EntityManagerInterface $em): JsonResponse 
    {
        if (!$chambre) {
            return new JsonResponse(['message' => 'Chambre non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        try {
            $em->remove($chambre);
            $em->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Erreur lors de la suppression de la chambre.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'La chambre a été supprimée avec succès.'], Response::HTTP_NO_CONTENT);
    }
    
    
    #[Route('/chambres', name: 'createChambre', methods: ['POST'])]
    public function createChambre(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jsonData = $request->getContent();

        $data = json_decode($jsonData, true);

        if (!isset($data['categorie']) || !isset($data['prix_nuit']) || !isset($data['capacite']) || !isset($data['caracteristiques'])) {
            return new JsonResponse(['message' => 'Toutes les informations requises doivent être fournies.'], Response::HTTP_BAD_REQUEST);
        }

        $chambre = new Chambres();
        $chambre->setCategorie($data['categorie']);
        $chambre->setPrixNuit($data['prix_nuit']);
        $chambre->setCapacite($data['capacite']);
        $chambre->setCaracteristiques($data['caracteristiques']);

        try {
            $entityManager->persist($chambre);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Une erreur est survenue lors de la création de la chambre.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'La chambre a été créée avec succès.'], Response::HTTP_CREATED);
    }

    #[Route('/chambres/{id}', name: 'updateChambre', methods: ['PUT'])]
    public function updateChambre(Chambres $chambre, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$chambre) {
            return new JsonResponse(['message' => 'Chambre non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $jsonData = $request->getContent();

        $data = json_decode($jsonData, true);

        if (!isset($data['categorie']) || !isset($data['prix_nuit']) || !isset($data['capacite']) || !isset($data['caracteristiques'])) {
            return new JsonResponse(['message' => 'Toutes les informations requises doivent être fournies.'], Response::HTTP_BAD_REQUEST);
        }

        $chambre->setCategorie($data['categorie']);
        $chambre->setPrixNuit($data['prix_nuit']);
        $chambre->setCapacite($data['capacite']);
        $chambre->setCaracteristiques($data['caracteristiques']);

        try {
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Une erreur est survenue lors de la mise à jour de la chambre.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'La chambre a été mise à jour avec succès.'], Response::HTTP_OK);
    }
}
