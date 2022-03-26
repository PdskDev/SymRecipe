<?php

namespace App\Controller;

use App\Entity\User;
use Monolog\Handler\Handler;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
     
    #[Route('/inscription', name: 'security.registration', methods:['GET', 'POST'])]
    public function registration(Request $request, EntityManagerInterface $manager): Response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']); 
        
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            //$user = $form->getData();
            //$manager->persist($user);
            $manager->persist($form->getData());
            $manager->flush();

        $this->addFlash(
           'success',
           'Votre inscription a été effectuée avec succès.'
        );
        return $this->redirectToRoute('security.login');
            
        }

        return $this->render('pages/security/registration.html.twig', [
            'form' => $form->createView()  
        ]);
    }
}
