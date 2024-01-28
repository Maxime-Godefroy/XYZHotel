<?php

namespace App\Controller;

use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ApiService $apiService, Request $request): Response
    {
        $session = $request->getSession();
        $user = $session->get('user');

        //dd($user);
        $data = $apiService->get();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'data' => $data,
            'user' => $user,
        ]);
    }

    #[Route('/connexion', name: 'app_connexion')]
    public function connexion(Request $request, ApiService $apiService): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('adresseMail');
            $password = $request->request->get('motDePasse');
        
            if (!empty($email)) {
                $clients = $apiService->get('clients');
                $userFound = false;
        
                foreach ($clients as $client) {
                    if ($client['adresseMail'] === $email) {
                        $userFound = true;
                        if (password_verify($password, $client['motDePasse'])) {
                            $session = $request->getSession();
                            $session->set('user', $client);
        
                            return $this->redirectToRoute('app_home');
                        } else {
                            $this->addFlash('error', 'Mot de passe incorrect');
                            break;
                        }
                    }
                }
        
                if (!$userFound) {
                    $this->addFlash('error', 'Adresse mail inexistante');
                }
            }
        }

        return $this->render('home/connexion.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(ApiService $apiService): Response
    {
        $data = $apiService->get();
        return $this->render('home/inscription.html.twig', [
            'controller_name' => 'HomeController',
            'data' => $data,
        ]);
    }

    #[Route('/deconnexion', name: 'app_deconnexion')]
    public function deconnexion(Request $request): Response
    {
        
        $session = $request->getSession();
        $session->remove('user');

        return $this->redirectToRoute('app_home');
    }

    #[Route('/compte', name: 'app_compte')]
    public function compte(Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->has('user')) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/compte.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
