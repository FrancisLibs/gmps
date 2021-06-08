<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class DefaultController extends AbstractController
{
    /**
     * Home page
     * 
     * @Route("/", name="home")
     * @return RedirectResponse|Response
     */
    public function index(OrderRepository $orderRepository): Response
    {
        $orders=$orderRepository->lateOrder(); // Commandes en retard
        return $this->render('default/default.html.twig', [
            'orders'    =>  $orders
        ]);
    }
}