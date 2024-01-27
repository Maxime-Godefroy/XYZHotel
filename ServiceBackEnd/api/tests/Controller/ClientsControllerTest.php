<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientsControllerTest extends WebTestCase
{
    private $clientId;

    public function testCreateClient()
    {
        $client = static::createClient();
        $data = [
            'nom' => 'Leroy',
            'prenom' => 'Marie',
            'email' => 'marie.leroy@example.com',
            'telephone' => '0123456789',
            'mot_de_passe' => 'password123',
        ];

        $client->getRequest('POST', '/clients', [], [], [], json_encode($data));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->clientId = $response['id'];
    }

    public function testGetClient()
    {
        $client = static::createClient();

        $client->getRequest('GET', '/clients/' . $this->clientId);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testUpdateClient()
    {
        $client = static::createClient();
        $data = [
            'nom' => 'Martin',
            'prenom' => 'Paul',
            'email' => 'paul.martin@example.com',
            'telephone' => '0987654321',
            'mot_de_passe' => 'newpassword123',
        ];

        $client->getRequest('PUT', '/clients/' . $this->clientId, [], [], [], json_encode($data));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testDeleteClient()
    {
        $client = static::createClient();

        $client->getRequest('DELETE', '/clients/' . $this->clientId);

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
    }
}