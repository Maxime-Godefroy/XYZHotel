<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function get($route = null, $id = null)
    {
        if ($route == null) {
            $url = 'http://127.0.0.1:8000';
        } else {
            $url = 'http://127.0.0.1:8000/'.$route;
            if ($id != null) {
                $url .= "/".$id;
            }
        }

        $response = $this->client->request('GET', $url);

        if ($response->getStatusCode() === 200) {
            return $response->toArray();
        }

        return null;
    }
}