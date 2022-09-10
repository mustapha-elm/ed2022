<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressFormType;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountAddressController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    #[Route('/mon-compte/mes-adresses', name: 'app_account_address')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('account/address.html.twig');
    }


    #[Route('/mon-compte/nouvelle-adresse', name: 'app_add_address')]
    public function add(Request $request, CartService $cartService): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $address = new Address();
        $user = $this->getUser();
        $address->setReceiver($user->getFullName());
        $form = $this->createForm(AddressFormType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($user);
            $address->setAvailable(1);
            $address->setName(strtoupper($address->getName()));
            $address->setCity(strtoupper($address->getCity()));
            
            $this->em->persist($address);
            $this->em->flush();

            if ($cartService->getCart()) {
               return $this->redirectToRoute('app_order');
            }

            return $this->redirectToRoute('app_account_address');
        }

        return $this->render('account/form-address.html.twig', [
            'form' => $form->createView(),
            'formTitle' => 'Ajouter une nouvelle adresse'
        ]);
    }


    #[Route('/mon-compte/modifier-adresse/{id}', name: 'app_update_address')]
    public function update(Request $request, $id): Response
    {
        $address = $this->em->getRepository(Address::class)->findOneBy(['id' => $id]);
        
        if (!$address || $this->getUser() != $address->getUser()) {
            return $this->redirectToRoute('app_account_address');
        }

        $form = $this->createForm(AddressFormType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address->setName(strtoupper($address->getName()));
            $address->setCity(strtoupper($address->getCity()));
            
            $this->em->flush();

            return $this->redirectToRoute('app_account_address');
        }
        

        return $this->render('account/form-address.html.twig', [
            'form' => $form->createView(),
            'formTitle' => 'Modifier cette adresse'
        ]);
    }


    #[Route('/mon-compte/supprimer-adresse/{id}', name: 'app_delete_address')]
    public function delete(Request $request, $id): Response
    {
        $address = $this->em->getRepository(Address::class)->findOneBy(['id' => $id]);
        
        if (!$address || $this->getUser() != $address->getUser()) {
            return $this->redirectToRoute('app_account_address');
        }

        $this->em->getRepository(Address::class)->remove($address);
        $this->em->flush();
       
        return $this->redirectToRoute('app_account_address');
        
        

        
    }


}
