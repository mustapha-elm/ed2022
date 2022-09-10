<?php

namespace App\Controller;


use App\Entity\Order;
use App\Service\CartService;
use App\Service\MailjetService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{
    private $em;
    private $mj;

    public function __construct(EntityManagerInterface $em, MailjetService $mj)
    {
        $this->em = $em;
        $this->mj = $mj;
    }
    
    #[Route('/commande/merci/{checkoutSessionId}', name: 'app_order_success')]
    public function index($checkoutSessionId, CartService $cartService): Response
    {
        $order = $this->em->getRepository(Order::class)->findOneBy(['stripeSessionId' => $checkoutSessionId]);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        if(!$order->isPaid()) {
            $order->setPaid(1);
            $this->em->flush();
            $cartService->removeCart();
            //envoi mail
            
            $this->mj->send($this->getUser()->getEmail(), $this->getUser()->getFirstName(), 'Merci pour votre commande n°' .$order->getReference(), 'Merci pour votre commande n°' .$order->getReference());
            $this->mj->send('contact@electrodeals.tech', '', 'attention une commande a été passée !', 'reference de la commande :' . $order->getReference());
        }

        return $this->render('order/success.html.twig', [
            'order' => $order,
        ]);
    }


    #[Route('/commande/echec/{checkoutSessionId}', name: 'app_order_cancel')]
    public function cancel($checkoutSessionId): Response
    {
        $order = $this->em->getRepository(Order::class)->findOneBy(['stripeSessionId' => $checkoutSessionId]);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('order/cancel.html.twig', [
            'order' => $order,
        ]);
    }
}
