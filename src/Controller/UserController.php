<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
    #[Security("is_granted('ROLE_USER') and user === presentUser")]
    public function edit(User $presentUser, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        //Vérifier si l'utilisateur est connecté
        //if(!$this->getUser()){
        //    return $this->redirectToRoute('security.login');
        //}

        //Vérifier si c'est le même utilisateur connecté
        //if($this->getUser() !== $presentUser){
        //    return $this->redirectToRoute('recette.index');
        //}
        
        $form = $this->createForm(UserType::class, $presentUser);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 

            if($hasher->isPasswordValid($presentUser, $form->getData()->getPlainPassword())){

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
    #[Security("is_granted('ROLE_USER') and user === presentUser")]
    //public function editPassword(User $user, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    public function editPassword(User $presentUser, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        //Vérifier si l'utilisateur est connecté
        //if(!$this->getUser()){
        //    return $this->redirectToRoute('security.login');
        //}

        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 

            if($hasher->isPasswordValid($presentUser, $form->getData()['oldPassword'])){

                $newPasswordHashed =  $hasher->hashPassword(
                    $presentUser,
                    $form->getData()['newPassword']
                );

                $presentUser->setPassword($newPasswordHashed);

                $manager->persist($presentUser);
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
