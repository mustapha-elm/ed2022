<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService {

    private $requestStack;
    private $em;

    public function __construct(EntityManagerInterface $em, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->requestStack = $requestStack;
    }


     // methode pour recupérer le panier (id-produit et quantité)
     public function getCart() {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart');
        return $cart;
    }


    // methode qui recupere le panier complet (produits et quantité). 
    public function getCartComplete(): array {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart');
        $cartComplete = [];
        if (!empty($cart)) {
            foreach($cart as $id => $qte) {
                $product = $this->em->getRepository(Product::class)->findOneBy(['id' => $id]);
                if (!$product) {
                    unset($cart[$id]);
                    $session->set('cart', $cart);
                } else {
                    $cartComplete[] = ['product' => $product, 'qte' => $qte];
                }               
            }
        }
        return $cartComplete;
    }


    // methode pour ajouter un produit au panier
    public function addToCart($id) {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $session->set('cart', $cart);
    }


    //methode pour vider le panier
    public function removeCart() {
        $session = $this->requestStack->getSession();
        $session->remove('cart');
    }


       // methode pour supprimer un produit du panier
       public function deleteProductToCart($id) {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart');
        unset($cart[$id]);
        $session->set('cart', $cart);
    }


    // methode pour diminuer la quantité d'un produit au panier
    public function decreaseToCart($id) {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart');
        if ($cart[$id] > 1) {
            $cart[$id]-- ;
        } else {
            unset($cart[$id]);
        }
        $session->set('cart', $cart);
    }
    
}