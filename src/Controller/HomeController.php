<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $products = $this->em->getRepository(Product::class)->findBy(['best' => true]);

        
        
        return $this->render('home/index.html.twig', [
            'products' => $products
        ]);
    }
}
