<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChambresControllerTest extends WebTestCase
{
    private $chambreId;

    public function testCreateChambre()
    {
        $client = static::createClient();
        $data = [
            'categorie' => 'Suite',
            'prix_nuit' => 200,
            'capacite' => 4,
            'caracteristiques' => 'Vue sur mer, Mini bar, Wi-Fi',
        ];

        $client->getRequest('POST', '/chambres', [], [], [], json_encode($data));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->chambreId = $response['id'];
    }

    public function testGetChambre()
    {
        $client = static::createClient();

        $client->getRequest('GET', '/chambres/' . $this->chambreId);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testUpdateChambre()
    {
        $client = static::createClient();
        $data = [
            'categorie' => 'Chambre double',
            'prix_nuit' => 150,
            'capacite' => 2,
            'caracteristiques' => 'Vue sur jardin, Wi-Fi',
        ];

        $client->getRequest('PUT', '/chambres/' . $this->chambreId, [], [], [], json_encode($data));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testDeleteChambre()
    {
        $client = static::createClient();

        $client->getRequest('DELETE', '/chambres/' . $this->chambreId);

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
    }
}