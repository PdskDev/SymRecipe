<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    
    /**
     * This controller allow us to modify profil information
     *
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/utilisateur/edition/{id}', name: 'user.edit', methods:['GET', 'POST'])]
    public function edit(User $user, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        //Vérifier si l'utilisateur est connecté
        if(!$this->getUser()){
            return $this->redirectToRoute('security.login');
        }

        //Vérifier si c'est le même utilisateur connecté
        if($this->getUser() !== $user){
            return $this->redirectToRoute('recette.index');
        }
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 

            if($hasher->isPasswordValid($user, $form->getData()->getPlainPassword())){

                $manager->persist($form->getData());
                $manager->flush();
                $this->addFlash(
                    'success',
                    'Votre profil a été modifié avec succès.');
                }
            else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe saisi est incorrect.');
            }
        }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
 
    #[Route('/utilisateur/changermotdepasse/{id}', name: 'user.edit.password', methods:['GET', 'POST'])]
    //public function editPassword(User $user, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    public function editPassword(User $user, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        //Vérifier si l'utilisateur est connecté
        if(!$this->getUser()){
            return $this->redirectToRoute('security.login');
        }

        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 

            if($hasher->isPasswordValid($user, $form->getData()['oldPassword'])){

                $newPasswordHashed =  $hasher->hashPassword(
                    $user,
                    $form->getData()['newPassword']
                );

                $user->setPassword($newPasswordHashed);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre mot de passe a été modifié avec succès.');
                }
            else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe saisi est incorrect.');
            }

            return $this->render('pages/user/editpassword.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $this->render('pages/user/editpassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
