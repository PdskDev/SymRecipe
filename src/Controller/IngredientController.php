<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Form\IngredientsType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\IngredientsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IngredientController extends AbstractController
{
    /**
     * This function display all ingredient
     *
     * @param IngredientsRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */

    #[Route('/ingredients', name: 'ingredient.index', methods:['GET'])]
    public function index(IngredientsRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $ingredients = $paginator->paginate(
            $repository->findAll(), 
            $request->query->getInt('page', 1), 
            10
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients
        ]);
    }

    #[Route('/ingredients/nouveau', name:'ingredient.new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response {

        $ingredients = new Ingredients();
        $form = $this->createForm(IngredientsType::class, $ingredients);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $ingredients = $form->getData();
            $manager->persist($ingredients);
            $manager->flush();

            $this->addFlash(
               'success',
               'Votre ingrédient a été créé avec succès.'
            );
            return $this->redirectToRoute('ingredient.index');
        }
        
        return $this->render('pages/ingredient/new.html.twig', 
        [
            'form' => $form->createView(),
        ]);
    }

   
   #[Route('/ingredients/edition/{id}', name:'ingredient.edit', methods:['GET', 'POST'])]
   //public function edit(IngredientsRepository $repository, int $id): Response
   public function edit(Ingredients $ingredient, Request $request, EntityManagerInterface $manager): Response
   {
      //$ingredient = $repository->findOneBy(['id' => $id]);
      $form = $this->createForm(IngredientsType::class, $ingredient);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
         
        $ingredients = $form->getData();
        $manager->persist($ingredients);
        $manager->flush();

        $this->addFlash(
           'success',
           'Votre ingrédient a été modifié avec succès.'
        );
        return $this->redirectToRoute('ingredient.index');
    }
      
      return $this->render('pages/ingredient/edit.html.twig', [
          'form' => $form->createView()
      ]);
   }

   #[Route('/ingredients/suppression/{id}', name:'ingredient.delete', methods:['GET'])]
   public function delete(EntityManagerInterface $manager, Ingredients $ingredient): Response
   {
       if(!$ingredient){

        $this->addFlash(
            'success',
            'L\'ingrédient à supprimer n\'existe pas');

           return $this->redirectToRoute('ingredient.index');
       }
       else {
        $manager->remove($ingredient);
        $manager->flush();
        $this->addFlash(
         'success',
         'Votre ingrédient a été supprimé avec succès.'
      );
 
        return $this->redirectToRoute('ingredient.index');
       }
      
   }

}
