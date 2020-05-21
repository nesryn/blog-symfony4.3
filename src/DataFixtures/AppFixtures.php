<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private  $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder= $passwordEncoder;
    }

    /**
     * load data fixtures
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
    $this->loadUsers($manager);
    $this->loadBlogPosts($manager);

    }

    public function loadBlogPosts(ObjectManager $manager)
    {
        $user=$this->getReference('user_admin');
        $blogPost =new BlogPost();
        $blogPost->setTitle("first post")
            ->setPublished(new \DateTime('now'))
            ->setContent("content")
            ->setSlug("slug")
            ->setAuthor($user);
        $manager->persist($blogPost);
        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {

    }

    public function loadUsers(ObjectManager $manager)
    {
        $user =new User();
        $user->setUsername('admin')
            ->setName('name')
            ->setEmail('email@email.com')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,'admin')
            )
            ;
        $this->addReference('user_admin',$user);
        $manager->persist($user);
        $manager->flush();
    }

}
