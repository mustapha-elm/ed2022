<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/produits', name: 'app_product')]
    public function index(Request $request): Response
    {
        $products = $this->em->getRepository(Product::class)->findAll();
        
        if ($request->get('btn-search')) {
            $products = $this->em->getRepository(Product::class)->findWithSearch($request->get('search-product'));
        }

        

        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/produits/{slug}', name: 'app_product_details')]
    public function details($slug): Response
    {
        $product = $this->em->getRepository(Product::class)->findOneBy(['slug' => $slug]);
    
        return $this->render('product/details.html.twig', [
            'product' => $product
        ]);
    }
}
