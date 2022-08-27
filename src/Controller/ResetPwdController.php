<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\ResetPwd;
use App\Form\EmailCheckType;
use App\Service\MailjetService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ResetPwdController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    #[Route('/reinitialiser-mot-de-passe', name: 'app_demand_reset_pwd')]
    public function index(Request $request, MailjetService $mjs): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('app_account');
        }

        $alert = [];
        $form = $this->createForm(EmailCheckType::class);
        $form->handleRequest($request);
        $email = $form->get('email')->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
            if (!$user) {
                $alert = [
                    'type'=> 'Erreur!',
                    'message' => 'Cet email n\'est pas associé à un compte utilisateur',
                    'bgColor' => 'alert-danger'
                ];
            } else {
                $resetPwd = new ResetPwd();
                $resetPwd->setUser($user);
                $resetPwd->setToken(uniqid());
                $resetPwd->setCreatedAt(new DateTime());
                $this->em->persist($resetPwd);
                $this->em->flush();

                 //envoi email avec lien pour réinitialiser le mot de passe
                $url = $this->generateUrl('app_reset_pwd', ['token' => $resetPwd->getToken()]);
                $HTMLPart = 'Cliquez sur le lien suivant pour réinitialiser votre mot de passe :</br>';
                $HTMLPart .= '<a href="'. $url .'">Mettre à jour mon mot de passe</a>';
                $mjs->send($user->getEmail(), $user->getFirstname(), 'reset mdp', $HTMLPart);
                
                
                $alert = [
                    'type'=> 'Envoyé!',
                    'message' => 'Un email vient de vous être envoyé pour réinitialiser votre mot de passe.',
                    'bgColor' => 'alert-success'
                ];
            }
        }

        return $this->render('reset_pwd/index.html.twig', [
            'form' => $form->createView(),
            'alert' => $alert
        ]);
    }




    #[Route('/reinitialiser-mot-de-passe/{token}', name: 'app_reset_pwd')]
    public function resetPwd($token): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('app_account');
        }

        return $this->render('reset_pwd/reset.html.twig');
    }
}
