<?php

namespace App\Controller;

use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChambreController extends AbstractController
{
    #[Route('/chambre', name: 'app_chambre')]
    public function index(ApiService $apiService, ): Response
    {
        $data = $apiService->get();
        return $this->render('chambre/index.html.twig', [
            'controller_name' => 'ChambreController',
            'data' => $data,
        ]);
    }
}
