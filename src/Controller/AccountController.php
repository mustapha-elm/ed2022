<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    #[Route('/mon-compte', name: 'app_account')]
    public function index(): Response
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('account/index.html.twig');
    }


    #[Route('/mon-compte/changer-mot-de-passe', name: 'app_account_change_password')]
    public function changePassword(Request $request, UserPasswordHasherInterface $userPHI, EntityManagerInterface $em): Response
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $alert = [];
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPwd = $form->get('oldPwd')->getData();          
            $user = $this->getUser();

            if ($userPHI->isPasswordValid($user, $oldPwd)) {
                $newPwd = $form->get('newPwd')->getData();
                $user->setPassword($userPHI->hashPassword($user, $newPwd));
                $em->flush();
                $alert = [
                    'type'=> 'succès!',
                    'message' => 'Vous avez bien changer votre mot de passe.',
                    'bgColor' => 'alert-success'
                ];

            } else {
                $alert = [
                    'type'=> 'Erreur!',
                    'message' => 'Votre mot de passe actuel est incorect.',
                    'bgColor' => 'alert-danger'
                ];
            }
        }

        return $this->render('account/change-password.html.twig', [
            'changePasswordForm' => $form->createView(),
            'alert' => $alert
        ]);
    }
}
