<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use App\Entity\Article;
use App\Entity\User;
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
        $articles = [];
        for($i=1;$i<50;$i++){
            $article = new Article();
        $article->setName($this->faker->word())
                ->setDescription('ceci est la description'. $i)
                ->setTheme('le theme bonbon' .$i);
        
        $articles[] = $article;
        $manager->persist($article);
        }

        for($j=1;$j<10;$j++){
            $blog = new Blog();
            $blog->setName($this->faker->word())
                ->setCreateur('le Createur'. $j)
                ->setTheme('le theme de ce blog' .$j)
                ->setDetails($this->faker->text(300).$j)
                ->setIsFavorite(mt_rand(0, 1) === 1 ? true : false);

                for($k=1;$k< mt_rand(1, 5);$k++){
                    $blog->addArticle($articles[mt_rand(0,count($articles) - 1)]);
                }

        
        $manager->persist($blog);
        }

        for ($i=0; $i<10; $i++){
            $user = new User();
            $user->setFullName($this->faker->name())
                ->setPseudo(mt_rand(0,1) === 1 ? $this->faker->firstName() : null)
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');

            $manager->persist($user);
        }


        $manager->flush();
    }
}
