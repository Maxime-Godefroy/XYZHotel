<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\ComptesClients;
use App\Repository\ClientsRepository;
use App\Repository\ComptesClientsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ExchangeRateService;

class ComptesClientsController extends AbstractController
{
    #[Route('/comptes_clients', name: 'app_comptes_clients', methods: ['GET'])]
    public function getAllComptesClients(ComptesClientsRepository $comptesClientsRepository, SerializerInterface $serializer): JsonResponse
    {
        try {
            $comptesClientsList = $comptesClientsRepository->findAll();
            $jsonComptesClientsList = $serializer->serialize($comptesClientsList, 'json');

            return new JsonResponse($jsonComptesClientsList, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(
                ['message' => 'Une erreur est survenue lors de la récupération des comptes clients.'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/comptes_clients/{id}', name: 'detailCompteClient', methods: ['GET'])]
    public function getDetailCompteClient(ComptesClients $compteClient = null, SerializerInterface $serializer)
    {
        if (!$compteClient) {
            return new JsonResponse(['error' => 'Compte client introuvable'], Response::HTTP_NOT_FOUND);
        }

        try {
            $jsonCompteClient = $serializer->serialize($compteClient, 'json');
            return new JsonResponse($jsonCompteClient, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Une erreur est survenue lors de la sérialisation'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/comptes_clients/{id}', name: 'deleteCompteClient', methods: ['DELETE'])]
    public function deleteCompteClient(ComptesClients $compteClient, EntityManagerInterface $em): JsonResponse 
    {
        if (!$compteClient) {
            return new JsonResponse(['message' => 'Compte client non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        try {
            $em->remove($compteClient);
            $em->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Erreur lors de la suppression du compte client.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'Le compte client a été supprimé avec succès.'], Response::HTTP_NO_CONTENT);
    }
    
    
    #[Route('/comptes_clients', name: 'createCompteClient', methods: ['POST'])]
    public function createCompteClient(Request $request, EntityManagerInterface $entityManager, ExchangeRateService $exchangeRateService): Response
    {
        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);

        if (!isset($data['client_id']) || !isset($data['solde_portefeuille']) || !isset($data['devise'])) {
            return new JsonResponse(['message' => 'Toutes les informations requises doivent être fournies.'], Response::HTTP_BAD_REQUEST);
        }

        $client = $entityManager->getRepository(Clients::class)->find($data['client_id']);

        if (!$client) {
            return new JsonResponse(['message' => 'Client non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $soldeEuros = $exchangeRateService->convertToEuro($data['solde_portefeuille'], $data['devise']);

        $compteClient = new ComptesClients();
        $compteClient->setClient($client);
        $compteClient->setSoldePortefeuille($soldeEuros);

        try {
            $entityManager->persist($compteClient);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Une erreur est survenue lors de la création du compte client.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'Le compte client a été créé avec succès.'], Response::HTTP_CREATED);
    }


    #[Route('/comptes_clients/{id}', name: 'updateCompteClient', methods: ['PUT'])]
    public function updateCompteClient(ComptesClients $compteClient, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$compteClient) {
            return new JsonResponse(['message' => 'Compte client non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);

        if (!isset($data['client_id']) || !isset($data['solde_portefeuille'])) {
            return new JsonResponse(['message' => 'Toutes les informations requises doivent être fournies.'], Response::HTTP_BAD_REQUEST);
        }

        $client = $entityManager->getRepository(Clients::class)->find($data['client_id']);

        if (!$client) {
            return new JsonResponse(['message' => 'Client non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $compteClient->setClient($client);
        $compteClient->setSoldePortefeuille($data['solde_portefeuille']);

        try {
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Une erreur est survenue lors de la mise à jour du compte client.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'Le compte client a été mis à jour avec succès.'], Response::HTTP_OK);
    }
}
