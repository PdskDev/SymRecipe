<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{
    #[Route('/recette', name: 'recette.index', methods:['GET'])]
    public function index(RecipeRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $recettes = $paginator->paginate(
            $repository->findAll(), 
            $request->query->getInt('page', 1), 
            10
        );

        return $this->render('pages/recipe/index.html.twig', [
            'recettes' => $recettes,
        ]);
    }

    #[Route('/recette/nouvelle/', name: 'recette.new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response {

        $recettes= new Recipe();
        $form = $this->createForm(RecipeType::class, $recettes );
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $recettes = $form->getData();
            $manager->persist($recettes);
            $manager->flush();

            $this->addFlash(
               'success',
               'Votre recette a été créée avec succès.'
            );
            return $this->redirectToRoute('recette.index');
        }
        
        return $this->render('pages/recipe/new.html.twig', 
        [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/recette/edition/{id}', name:'recette.edit', methods:['GET', 'POST'])]
   //public function edit(IngredientsRepository $repository, int $id): Response
   public function edit(Recipe $recettes, Request $request, EntityManagerInterface $manager): Response
   {
      //$ingredient = $repository->findOneBy(['id' => $id]);
      $form = $this->createForm(RecipeType::class, $recettes);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
         
        $recettes = $form->getData();
        $manager->persist($recettes);
        $manager->flush();

        $this->addFlash(
           'success',
           'Votre ingrédient a été modifié avec succès.'
        );
        return $this->redirectToRoute('recette.index');
    }
      
      return $this->render('pages/recipe/edit.html.twig', [
          'form' => $form->createView()
      ]);
   }


   #[Route('/recette/suppression/{id}', name:'recette.delete', methods:['GET'])]
   public function delete(EntityManagerInterface $manager, Recipe $recettes): Response
   {
       if(!$recettes){

        $this->addFlash(
            'success',
            'Cette recette à supprimer n\'existe pas');

           return $this->redirectToRoute('recette.index');
       }
       else {
        $manager->remove($recettes);
        $manager->flush();
        $this->addFlash(
         'success',
         'Cette recette a été supprimée avec succès.'
      );
 
        return $this->redirectToRoute('recette.index');
       }
      
   }

    
}
