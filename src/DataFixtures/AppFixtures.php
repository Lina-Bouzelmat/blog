<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        for($i=1;$i<50;$i++){
            $article = new Article();
        $article->setName($this->faker->word())
                ->setDescription('ceci est la description'. $i)
                ->setTheme('le theme bonbon' .$i);
        
        $manager->persist($article);
        }
        $manager->flush();
    }
}
