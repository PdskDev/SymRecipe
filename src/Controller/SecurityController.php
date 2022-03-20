<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/connexion', name: 'security.login', methods:['GET', 'POST'])]
    //public function index(): Response
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        //Get logion error
        $error = $authenticationUtils->getLastAuthenticationError();

        //last username entered
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('pages/security/login.html.twig', [
            'controller_name' => 'SecurityController',
            'lastusername' => $lastUsername,
            'error' => $error,
        ]);
    }

    //Logout
    #[Route('/deconnexion', name: 'security.logout', methods:['GET'])]
    public function logout()
    {
        #Nothing to do
    }
}
