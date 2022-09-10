<?php

namespace App\Controller;

use DateTime;
use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderDetail;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/commande', name: 'app_order')]
    public function index(CartService $cartService): Response
    {
      
        if (!$this->getUser()->getAddresses()->getValues()) {
            return $this->redirectToRoute('app_add_address');
        }

        $form = $this->createForm(OrderType::class, null, [ 'user' => $this->getUser() ]);
        $cart = $cartService->getCartComplete();

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart
        ]);
    }

    #[Route('/commande/paiement', name: 'app_order_pay', methods:['POST'])]
    public function addOrder(CartService $cartService, Request $request): Response
    {
        $form = $this->createForm(OrderType::class, null, [ 'user' => $this->getUser() ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
             //recuperation des données pour construire new Order()
             $date = new DateTime();
             $carrier = $form->get('carrier')->getData();
             $deliveryObject = $form->get('address')->getData();
             $delivery = $deliveryObject->getReceiver(). ', ';
             $delivery .= $deliveryObject->getStreet(). ', ';
             if ($deliveryObject->getComplement()) {
                 $delivery .= $deliveryObject->getComplement(). ', ';
             }
             $delivery .= $deliveryObject->getCp(). ' ' . $deliveryObject->getCity() . ' - ' . $deliveryObject->getCountry();
             $uniqRef = $date->format('dmy') . uniqid('-');
             
             $order = new Order();
             $order->setUser($this->getUser());
             $order->setCreatedAt($date);
             $order->setCarrierName($carrier->getName());
             $order->setCarrierPrice($carrier->getPrice());
             $order->setDelivery($delivery);
             $order->setPaid(0);
             $order->setReference($uniqRef);
             $this->em->persist($order);

             foreach ($cartService->getCartComplete() as $item) {
                $orderDetails = new OrderDetail();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProductName($item['product']->getName());
                $orderDetails->setQuantity($item['qte']);
                $orderDetails->setPrice($item['product']->getPrice());
                $orderDetails->setTotal($item['qte'] * $item['product']->getPrice());
                $this->em->persist($orderDetails);
            }

            $cart = $cartService->getCartComplete();

            $this->em->flush();
        }
       

        return $this->render('order/add-order.html.twig', [
            'carrier' => $carrier,
            'delivery' => $delivery,
            'cart' => $cart,
            'reference' => $order->getReference()
        ]);
    }
}
