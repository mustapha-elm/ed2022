<?php

namespace App\Controller;

use App\Entity\Product;
use App\Classe\ProductFilter;
use App\Entity\Category;
use App\Form\ProductFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

        $productFilter = new ProductFilter();
        $formFiltre = $this->createForm(ProductFilterType::class, $productFilter);

        $formFiltre->handleRequest($request);

        if ($formFiltre->isSubmitted() && $formFiltre->isValid()) {      
            if (!$productFilter->getCategories()) {
                $allCategories = $this->em->getRepository(Category::class)->findAll();
                $productFilter->setCategories($allCategories);
            }  
            if (!$productFilter->getStatement()) {
                $productFilter->addAllStatements();
            } 
           $products = $this->em->getRepository(Product::class)->findWithFilter($productFilter);
        }

        

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'formFiltre' => $formFiltre->createView()
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
