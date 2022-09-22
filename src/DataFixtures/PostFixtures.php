<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Helpers\Helper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture
{
    /**
     * loading Post data fixtures for symfony test
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
         $helper = new Helper();
         $post = new Post();
         $post->setDate(new \DateTime());
         $post->setTitle($helper->generateRandomString(20));
         $post->setContent($helper->generateRandomString(40));
         $post->setUser($helper->persistUser());
         $manager->persist($post);

         $manager->flush();
    }
}
