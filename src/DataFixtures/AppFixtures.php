<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Comment;
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

    /**
     * @var \Faker\Factory
     */
    private $faker;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder= $passwordEncoder;
        $this->faker = \Faker\Factory::create();
    }

    /**
     * load data fixtures
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
    $this->loadUsers($manager);
    $this->loadBlogPosts($manager);
    $this->loadComments($manager);

    }

    public function loadBlogPosts(ObjectManager $manager)
    {

        for ($i =0; $i<=50 ;$i++){
            $randrefUser = rand(0,25);

            $blogPost =new BlogPost();
            $blogPost->setTitle($this->faker->realText(30))
                ->setPublished(new \DateTime('now'))
                ->setContent($this->faker->realText())
                ->setSlug($this->faker->slug)
                ->setAuthor($this->getReference("user_admin_$randrefUser"));
            $this->setReference("blog_post_$i",$blogPost);
            $manager->persist($blogPost);
        }

        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {
        for ($i=0; $i<=50 ; $i++)
        {
            for ($j=0; $j< rand(1,3); $j++){
                $randrefUser = rand(0,25);
                $comment= new Comment();
                $comment
                    ->setContent($this->faker->realText())
                    ->setPublished($this->faker->dateTimeThisYear)
                    ->setBlogPost($this->getReference("blog_post_$i"))
                    ->setAuthor($this->getReference("user_admin_$randrefUser"));
                $manager->persist($comment);
            }

        }
        $manager->flush();

    }

    public function loadUsers(ObjectManager $manager)
    {
      for ($i=0; $i<= 25 ;$i++)
      {
          $user =new User();
          $user->setUsername($this->faker->realText(10))
              ->setName($this->faker->realText(15))
              ->setEmail($this->faker->email)
              ->setPassword($this->passwordEncoder->encodePassword(
                  $user,'admin')
              )
          ;
          $this->addReference("user_admin_$i",$user);
          $manager->persist($user);
      }

        $manager->flush();
    }

}
