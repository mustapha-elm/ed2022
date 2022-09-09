<?php

namespace App\Controller;

use App\Form\OrderType;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/commande', name: 'app_order')]
    public function index(CartService $cartService): Response
    {
        $form = $this->createForm(OrderType::class, null, [ 'user' => $this->getUser() ]);
        $cart = $cartService->getCartComplete();

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart
        ]);
    }

    #[Route('/commande/paiement', name: 'app_order_pay', methods:['POST'])]
    public function addOrder(CartService $cartService): Response
    {
        $form = $this->createForm(OrderType::class, null, [ 'user' => $this->getUser() ]);
       

        return $this->render('order/add-order.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
