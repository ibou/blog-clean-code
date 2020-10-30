<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class PostFixtures
 * @package App\DataFixtures
 */
class PostFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 123; $i++) {
            $post = new Post();
            $post->setTitle("Article N°" . $i);
            $post->setContent("Contenu N°" . $i);
            $post->setImage('https://via.placeholder.com/468x140?text=Visit+Blogging.com+Now');
            $manager->persist($post);

            for ($j = 1; $j <= rand(5, 15); $j++) {
                $comment = new Comment();
                $comment->setAuthor("Auteur " . $i);
                $comment->setContent("Commentaire N°" . $j);
                $comment->setPost($post);
                $manager->persist($comment);
            }
        }

        $manager->flush();
    }
}
