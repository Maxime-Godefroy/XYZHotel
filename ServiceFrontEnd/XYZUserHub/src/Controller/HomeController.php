<?php

namespace App\Controller;

use App\Service\ApiService;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
    public function inscription(Request $request, ApiService $apiService)
    {
        $form = $this->createFormBuilder()
            ->add('nom', TextType::class, [
                'required' => true,
            ])
            ->add('prenom', TextType::class, [
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'required' => true,
            ])
            ->add('telephone', TelType::class, [
                'required' => true,
            ])
            ->add('mot_de_passe', PasswordType::class, [
                'required' => true,
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $userExist = false;
            $users = $apiService->get('clients');

            foreach ($users as $user) {

                if ($user['adresseMail'] == $data['email']) {
                    $this->addFlash('error', 'Cet email est déjà utilisé.');
                    $userExist = true;
                }
            }

            if (!$userExist) {
                $apiService->post('clients', $data);

                $session = $request->getSession();
                $session->set('user', $data);

                $users = $apiService->get('clients');

                foreach ($users as $user) {
                    if ($user['adresseMail'] == $data['email']) {
                        $apiService->post('comptes_clients', ['client_id' => $user['id'], 'solde_portefeuille' => 0, 'devise' => 'EUR']);
                
                        $session->set('user', $user);
                    }
                }
                return $this->redirectToRoute('app_home');
            }
        }

        return $this->render('home/inscription.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'HomeController',
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
    public function compte(ApiService $apiService, Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->has('user')) {
            return $this->redirectToRoute('app_home');
        }


        $comptes = $apiService->get('comptes_clients');

        foreach ($comptes as $compte) {

            if ($compte['client']['id'] == $session->get('user')['id']) {
                $solde = $compte;
            }
        }

        if ($request->isMethod('POST')) {
            $montant = floatval($request->request->get('montant'));
            $devise = $request->request->get('devise');
            $putData = [
                'solde_portefeuille' => $montant,
                'devise' => $devise,
            ];
            $apiService->put("comptes_clients", $solde['id'], $putData);
            return $this->redirectToRoute('app_compte');
        }

        $reservations = $apiService->get("reservations");

        $userId = $session->get('user')['id'];

        $userReservations = array_filter($reservations, function ($reservation) use ($userId) {
            return $reservation['client']['id'] === $userId;
        });

        foreach ($userReservations as $reservation) {
            if (isset($reservation['reservation']) && isset($reservation['chambre'])) {
                $reservation['reservation']['chambre'] = $reservation['chambre'];
            }
        }

        $chambresReservees = $apiService->get("chambres_reservees");

        foreach ($userReservations as &$reservation) {
            foreach ($chambresReservees as $chambreReservee) {
                if ($chambreReservee['reservation']['id'] === $reservation['id']) {
                    $reservation['chambre'] = $chambreReservee['chambre'];
                    break;
                }
            }
        }
        unset($reservation);

        return $this->render('home/compte.html.twig', [
            'controller_name' => 'HomeController',
            'solde' => $solde,
            'userReservations' => $userReservations,
        ]);
    }

    #[Route('/compte_confirm_reservation/{id}', name: 'app_confirm_reservation', methods: ['POST'])]
    public function confirm($id, ApiService $apiService)
    {
        $apiService->put("reservations_confirmer", $id);
        return $this->redirectToRoute('app_compte');
    }

    #[Route('/compte_remove_reservation/{id}', name: 'app_remove_reservation', methods: ['POST'])]
    public function remove($id, ApiService $apiService)
    {
        $apiService->delete("reservations_annuler", $id);
        return $this->redirectToRoute('app_compte');
    }
}
