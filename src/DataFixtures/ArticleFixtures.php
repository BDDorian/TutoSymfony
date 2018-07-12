<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
      
            //Générer un espace faker avec du contenu en français.
            $faker= \Faker\Factory::create('fr_FR');

            //Création de trois catégories fakes.

            for($i =1; $i <=3; $i++){
                $category = new Category();
                $category->setTitle($faker->sentence())
                         ->setDescription($faker->paragraph());
                $manager->persist($category); 

            //Création de 4 à 6 articles maximum.    
            for($j = 0; $j <=mt_rand(4,6); $j++){    
            
            $article= new Article();

            $content = '<p>';
            $content .= join($faker->paragraphs(5), '</p><p>');
            $content .= '</p>';


            $article->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setCategory($category);
            //Préparation de l'envoie des données de l'article défini au préalable.
            $manager->persist($article);   
            
            //Envoi des commentaires
            for($k=0; $k <= mt_rand(4,10); $k++){

                $comment= new Comment();

                $content = '<p>';
                $content .= join($faker->paragraphs(2), '</p><p>');
                $content .= '</p>';

                $now = new \DateTime();
                //Prendre l'interval entre la date de création de l'article et celle du commentaire.
                $interval = $now->diff($article->getCreatedAt());

                $days = $interval->days;

                $minimum = '-' . $days . ' days';

                $comment->setAuthor($faker->name)
                        ->setContent($content)
                        ->setCreatedAt($faker->dateTimeBetween($minimum))
                        ->setArticle($article);

                $manager->persist($comment);        

            }

            }
        
        
    }
    //Envoie définitif des données à la BDD.
    $manager->flush();
}
}
