<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Ingredients;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    private Generator $faker;

        public function __construct()
        {
            $this->faker = Factory::create('fr_FR');
        }

    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i <=50 ; $i++) { 

        $ingredients = new Ingredients();
        $ingredients->setName($this->faker->word())
        ->setPrice(mt_rand(1.5, 100.0));
        $manager->persist($ingredients);
        }
        $manager->flush();
    }
}
