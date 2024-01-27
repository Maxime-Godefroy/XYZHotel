<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ComptesClientsControllerTest extends WebTestCase
{
    private $compteClientId;

    public function testCreateCompteClient()
    {
        $client = static::createClient();
        $data = [
            'client_id' => 1,
            'solde' => 1000.00,
            'devise' => 'EUR',
        ];

        $client->getRequest('POST', '/comptes_clients', [], [], [], json_encode($data));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->compteClientId = $response['id'];
    }

    public function testGetCompteClient()
    {
        $client = static::createClient();

        $client->getRequest('GET', '/comptes_clients/' . $this->compteClientId);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testUpdateCompteClient()
    {
        $client = static::createClient();
        $data = [
            'solde' => 2000.00,
            'devise' => 'USD',
        ];

        $client->getRequest('PUT', '/comptes_clients/' . $this->compteClientId, [], [], [], json_encode($data));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testDeleteCompteClient()
    {
        $client = static::createClient();

        $client->getRequest('DELETE', '/comptes_clients/' . $this->compteClientId);

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
    }
}