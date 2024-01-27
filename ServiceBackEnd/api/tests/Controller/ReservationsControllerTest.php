<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationsControllerTest extends WebTestCase
{
    private $reservationId;

    public function testEffectuerReservation()
    {
        $client = static::createClient();
        $data = [
            'client_id' => 1, // Remplacez par l'ID d'un client existant
            'date_checkin' => '2022-12-01',
            'nombre_nuits' => 2,
            'chambres' => 1,
        ];

        $client->getRequest('POST', '/reservations_effectuer', [], [], [], json_encode($data));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->reservationId = $response['id'];
    }

    public function testGetReservation()
    {
        $client = static::createClient();

        $client->getRequest('GET', '/reservations/' . $this->reservationId);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testUpdateReservation()
    {
        $client = static::createClient();
        $data = [
            'date_checkin' => '2022-12-02',
            'nombre_nuits' => 3,
            'chambres' => 2,
        ];

        $client->getRequest('PUT', '/reservations/' . $this->reservationId, [], [], [], json_encode($data));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testDeleteReservation()
    {
        $client = static::createClient();

        $client->getRequest('DELETE', '/reservations/' . $this->reservationId);

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
    }
}