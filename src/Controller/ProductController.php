<?php

namespace App\Controller;

use App\Service\MailjetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/produits', name: 'app_product')]
    public function index(MailjetService $mjs): Response
    {
        //$mjs->send('zemou59@hotmail.fr', 'mous', '1er ail de test', 'test de contenu <h2> avec titre de test </h2> en html', 'text simple !!!!!');
        return $this->render('product/index.html.twig');
    }
}
