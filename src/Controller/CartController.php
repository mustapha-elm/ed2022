<?php

namespace App\Controller;

use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    private $cartService;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->entityManager = $entityManager;
    }


    #[Route('/panier', name: 'app_cart')]
    public function index(): Response
    {
        $cartComplete = $this->cartService->getCartComplete();
        return $this->render('cart/index.html.twig', [
            'cart' => $cartComplete
            ]);
    }


    #[Route('/panier/ajouter/{id}', name: 'app_cart_add')]
    public function add($id): Response
    {
        $this->cartService->addToCart($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/panier/diminuer/{id}', name: 'app_cart_decrease')]
    public function decreaseToCart($id): Response
    {
        $this->cartService->decreaseToCart($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/panier/supprimer/{id}', name: 'app_cart_delete_one')]
    public function deleteToCart($id): Response
    {
        $this->cartService->deleteProductToCart($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/panier/suppression', name: 'app_cart_delete_all')]
    public function removeCart(): Response
    {
        $this->cartService->removeCart();
        return $this->redirectToRoute('app_home');
    }
}
