<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Recipe;
use App\Entity\Ingredients;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    private Generator $faker;

        public function __construct()
        {
            $this->faker = Factory::create('fr_FR');
            //$this->hasher = $passwordHasher;
        }

    public function load(ObjectManager $manager): void
    { 
        $users =[];

        for ($l=0; $l < 15; $l++) { 

            $user = new User();

            $user->setFullName($this->faker->lastName())
            ->setPseudo($this->faker->firstName())
            ->setEmail($this->faker->email())
            ->setRoles(['ROLE_USER'])
            ->setPlainPassword('password');

            $users[]=$user;
            
            $manager->persist($user);
        }

        //Fixtures for Ingrédients
        $ingredient = [];

        for ($i=0; $i <=50 ; $i++) { 

        $ingredients = new Ingredients();
        $ingredients->setName($this->faker->word())
        ->setPrice(mt_rand(1.5, 100.0))
        ->setUser($users[mt_rand(0 , count($users) - 1)]);

        //Ajout de l'ingrédient dans le tableau
        $ingredient[] = $ingredients;

        $manager->persist($ingredients);
        }

        //Recipe
        for ($j=0; $j < 25; $j++) { 

            $recipe = new Recipe();

            $recipe->setName($this->faker->word())
            ->setTime(mt_rand(0, 1) == 1 ? mt_rand(1, 1440) : null)
            ->setNbPeople(mt_rand(0, 1) == 1 ? mt_rand(1, 50) : null)
            ->setDifficulty(mt_rand(0, 1) == 1 ? mt_rand(1, 5) : null)
            ->setDescription($this->faker->text(300))
            ->setPrice(mt_rand(0, 1) == 1 ? mt_rand(1, 1000) : null)
            ->setIsFavorite(mt_rand(0, 1) == 1 ? true : false)
            ->setUser($users[mt_rand(0 , count($users) - 1)]);;

            for ($k=0; $k < mt_rand(5, 15) ; $k++) { 
                $recipe->addIngredient($ingredient[mt_rand(0 , count($ingredient) - 1)]);

            }
            $manager->persist($recipe);
        }

        

        $manager->flush();
    }
}