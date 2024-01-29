<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiService
{
    private $client;
    private $url;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->url = 'http://127.0.0.1:8000/';
    }

    public function get($route = null, $id = null)
    {
        $url = $this->url;
        if ($route != null) {
            $url .= $route;
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

    public function post($route, $data)
    {
        $url = $this->url . $route;
    
        $response = $this->client->request('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($data),
        ]);
    
        if ($response->getStatusCode() === 200) {
            return $response->toArray();
        }
    
        return null;
    }

    public function put($route, $id, $data = [])
    {
        $url = $this->url . $route . "/" . $id;

        $requestOptions = [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];

        if (!empty($data)) {
            $requestOptions['body'] = json_encode($data);
        }

        $response = $this->client->request('PUT', $url, $requestOptions);

        if ($response->getStatusCode() === 200) {
            return $response->toArray();
        }

        return null;
    }

    public function delete($route, $id, $data = [])
    {
        $url = $this->url . $route . "/" . $id;

        $requestOptions = [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];

        if (!empty($data)) {
            $requestOptions['body'] = json_encode($data);
        }

        $response = $this->client->request('DELETE', $url, $requestOptions);

        if ($response->getStatusCode() === 200) {
            return $response->toArray();
        }

        return null;
    }
}