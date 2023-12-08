<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Repository\ClientsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ClientsController extends AbstractController
{
    #[Route('/clients', name: 'app_clients', methods: ['GET'])]
    public function getAllClients(ClientsRepository $clientsRepository, SerializerInterface $serializer): JsonResponse
    {
        try {
            $clientsList = $clientsRepository->findAll();
            $jsonClientsList = $serializer->serialize($clientsList, 'json');

            return new JsonResponse($jsonClientsList, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(
                ['message' => 'Une erreur est survenue lors de la récupération des clients.'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/clients/{id}', name: 'detailClient', methods: ['GET'])]
    public function getDetailClient(Clients $clients = null, SerializerInterface $serializer)
    {
        if (!$clients) {
            return new JsonResponse(['error' => 'Client introuvable'], Response::HTTP_NOT_FOUND);
        }

        try {
            $jsonClient = $serializer->serialize($clients, 'json');
            return new JsonResponse($jsonClient, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Une erreur est survenue lors de la sérialisation'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/clients/{id}', name: 'deleteClient', methods: ['DELETE'])]
    public function deleteClient(Clients $clients, EntityManagerInterface $em): JsonResponse 
    {
        if (!$clients) {
            return new JsonResponse(['message' => 'Client non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        try {
            $em->remove($clients);
            $em->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Erreur lors de la suppression du client.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'Le client a été supprimé avec succès.'], Response::HTTP_NO_CONTENT);
    }
    
    
    #[Route('/clients', name: 'createClient', methods: ['POST'])]
    public function createClient(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);

        if (!isset($data['nom']) || !isset($data['prenom']) || !isset($data['email']) || !isset($data['telephone']) || !isset($data['mot_de_passe'])) {
            return new JsonResponse(['message' => 'Toutes les informations requises doivent être fournies.'], Response::HTTP_BAD_REQUEST);
        }

        $client = new Clients();
        $client->setNom($data['nom']);
        $client->setPrenom($data['prenom']);
        $client->setAdresseMail($data['email']);
        $client->setNumeroTelephone($data['telephone']);

        $hashedPassword = password_hash($data['mot_de_passe'], PASSWORD_BCRYPT);
        $client->setMotDePasse($hashedPassword);

        try {
            $entityManager->persist($client);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Une erreur est survenue lors de la création du client.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'Le client a été créé avec succès.'], Response::HTTP_CREATED);
    }

    #[Route('/clients/{id}', name: 'updateClient', methods: ['PUT'])]
    public function updateClient(Clients $client, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$client) {
            return new JsonResponse(['message' => 'Client non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);

        if (!isset($data['nom']) || !isset($data['prenom']) || !isset($data['email']) || !isset($data['telephone'])) {
            return new JsonResponse(['message' => 'Toutes les informations requises doivent être fournies.'], Response::HTTP_BAD_REQUEST);
        }

        $client->setNom($data['nom']);
        $client->setPrenom($data['prenom']);
        $client->setAdresseMail($data['email']);
        $client->setNumeroTelephone($data['telephone']);

        if (isset($data['mot_de_passe'])) {
            $hashedPassword = password_hash($data['mot_de_passe'], PASSWORD_BCRYPT);
            $client->setMotDePasse($hashedPassword);
        }

        try {
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Une erreur est survenue lors de la mise à jour du client.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'Le client a été mis à jour avec succès.'], Response::HTTP_OK);
    }
}
