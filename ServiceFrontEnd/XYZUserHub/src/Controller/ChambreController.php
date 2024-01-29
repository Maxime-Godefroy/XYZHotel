<?php

namespace App\Controller;

use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class ChambreController extends AbstractController
{
    #[Route('/chambre', name: 'app_chambre')]
    public function index(ApiService $apiService): Response
    {
        $data = $apiService->get();
        return $this->render('chambre/index.html.twig', [
            'controller_name' => 'ChambreController',
            'data' => $data,
        ]);
    }
    
    #[Route('/reservation/{id}', name: 'app_reservation')]
    public function reservation($id, Request $request, ApiService $apiService): Response
    {
        $session = $request->getSession();
        $form = $this->createForm(FormType::class);

        $form = $this->createFormBuilder()
            ->add('date_checkin', DateType::class, [
                'required' => true,
            ])
            ->add('nombre_nuits', NumberType::class, [
                'required' => true,
            ])
            ->getForm();
        

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $postData = [
                'client_id' => $session->get('user')['id'],
                'date_checkin' => $data['date_checkin']->format('Y-m-d'),
                'nombre_nuits' => $data['nombre_nuits'],
                'chambres' => $id,
            ];

            $apiService->post('reservations_effectuer', $postData);

            return $this->redirectToRoute('app_compte');
        }

        return $this->render('chambre/reservation.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'ChambreController',
        ]);
    }
}
