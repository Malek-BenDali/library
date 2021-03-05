<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr-FR');

        //creé 3 fake categorie
        for($i=1 ; $i <=3 ; $i++){
            $category = new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());

            $manager->persist($category);
        }

        
        //creé 10 fake article
        for($j=1; $j<=10 ; $j++ ){
            $article = new Article();
            $content ='<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';
            $article->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setNote($faker->numberBetween(2,5))
                    ->setCategory($category)
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setPrice($faker->randomFloat());
                    

            $manager->persist($article);    
        }

        //creé 10 comment
        for($k=1; $k <= 10; $k++){
            $comment = new Comment();
            $content ='<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>';

            $days = (new \DateTime())->diff($article->getCreatedAt())->days;

            $comment->setAuthor($faker->name)
                    ->setContent($content)
                    ->setCreatedAt($faker->dateTimeBetween('-' . $days .' days'))
                    ->setArticle($article);
            
                    $manager->persist($comment);
        }
        

        $manager->flush();
    }
}
