<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', 'home.index', methods:['GET'])]
    public function index(RecipeRepository $recipe_repo): Response
    {

    return $this->render('pages/home.html.twig', [
            'recettes' => $recipe_repo->findPublicRecipe(4)]);
    }


}