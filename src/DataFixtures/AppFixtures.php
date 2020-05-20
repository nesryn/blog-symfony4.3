<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * load data fixtures
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $blogPost =new BlogPost();
        $blogPost->setTitle("first post")
                 ->setPublished(new \DateTime('now'))
                 ->setContent("content")
                 ->setSlug("slug")
                 ->setAuthor("author");
        $manager->persist($blogPost);
        $manager->flush();
    }
}
