<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChambresReserveesControllerTest extends WebTestCase
{
    private $reservationId;

    public function testCreateReservation()
    {
        $client = static::createClient();
        $data = [
            'chambre_id' => 1,
            'date_debut' => '2022-01-01',
            'date_fin' => '2022-01-10',
            'client_id' => 1,
        ];

        $client->getRequest('POST', '/chambres_reservees', [], [], [], json_encode($data));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->reservationId = $response['id'];
    }

    public function testGetReservation()
    {
        $client = static::createClient();

        $client->getRequest('GET', '/chambres_reservees/' . $this->reservationId);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testUpdateReservation()
    {
        $client = static::createClient();
        $data = [
            'date_debut' => '2022-01-05',
            'date_fin' => '2022-01-15',
        ];

        $client->getRequest('PUT', '/chambres_reservees/' . $this->reservationId, [], [], [], json_encode($data));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testDeleteReservation()
    {
        $client = static::createClient();

        $client->getRequest('DELETE', '/chambres_reservees/' . $this->reservationId);

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
    }
}