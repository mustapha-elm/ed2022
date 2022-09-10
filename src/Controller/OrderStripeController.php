<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use App\Entity\Product;

use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderStripeController extends AbstractController
{
    #[Route('/commande/stripe', name: 'app_stripe')]
    public function index(EntityManagerInterface $em): Response
    {
        Stripe::setApiKey('sk_test_51Lgat9Cnz7jfn9jVqMS6xU4vehr1RjsGsTGCv9MyZo8ei729tkvlxJPV4KZuJYmShgMJCErvMIkHhbTxBsdCOj8600o2aycsFV');

        $YOUR_DOMAIN = 'http://localhost:8000';

        $order = $em->getRepository(Order::class)->findOneBy(['reference' => $_POST['reference']]);

        if (!$order) {
            throw $this->createNotFoundException('The order does not exist');
            // the above is just a shortcut for:
            // throw new NotFoundHttpException('The product does not exist');
        }

        $lcmdStripe = [];
        foreach ($order->getOrderDetails()->getValues() as $item) { 
            $product = $em->getRepository(Product::class)->findOneBy(['name' => $item->getProductName()]);          
            $lcmdStripe [] = [
                'price_data' => [
                    'currency' => 'EUR',
                    'product_data' => [
                        'name' => $item->getProductName(),
                        'images' => [$YOUR_DOMAIN . '/uploads/' . $product->getPicture()]
                    ],
                    'unit_amount' => $item->getPrice(),
                ],
                'quantity' => $item->getQuantity()
            ];
        }

        $lcmdStripe [] = [
            'price_data' => [
                'currency' => 'EUR',
                'product_data' => [
                    'name' => 'Frais de livraison (' .$order->getCarrierName() .')',
                    'images' => [$YOUR_DOMAIN . '/uploads/camion.png']
                ],
                'unit_amount' => $order->getCarrierPrice()*100,
            ],
            'quantity' => 1
        ];


        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => $lcmdStripe,
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/echec/{CHECKOUT_SESSION_ID}',
            ]);

        $order->setStripeSessionId($checkout_session->id);
        $em->flush();

        return $this->redirect($checkout_session->url);
    }
}
