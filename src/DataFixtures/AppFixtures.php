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

    private const USERS = [
        [
            'username' => 'admin',
            'email' => 'admin@blog.com',
            'name' => 'Piotr Jura',
            'password' => 'secret123#',
            'enabled' => true
        ],
        [
            'username' => 'john_doe',
            'email' => 'john@blog.com',
            'name' => 'John Doe',
            'password' => 'secret123#',

            'enabled' => true
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob@blog.com',
            'name' => 'Rob Smith',
            'password' => 'secret123#',

            'enabled' => true
        ],
        [
            'username' => 'jenny_rowling',
            'email' => 'jenny@blog.com',
            'name' => 'Jenny Rowling',
            'password' => 'secret123#',

            'enabled' => true
        ],
        [
            'username' => 'han_solo',
            'email' => 'han@blog.com',
            'name' => 'Han Solo',
            'password' => 'secret123#',

            'enabled' => false
        ],
        [
            'username' => 'jedi_knight',
            'email' => 'jedi@blog.com',
            'name' => 'Jedi Knight',
            'password' => 'secret123#',

            'enabled' => true
        ],
    ];

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
                ->setSlug($this->faker->slug);
            $authorRef=$this->getRandomUserReference();
            $blogPost->setAuthor($authorRef);
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
                    ->setBlogPost($this->getReference("blog_post_$i"));
                $authorRef=$this->getRandomUserReference();
                $comment->setAuthor($authorRef);
                $manager->persist($comment);
            }

        }
        $manager->flush();

    }

    public function loadUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $userFixture) {
            $user = new User();
            $user->setUsername($userFixture['username']);
            $user->setEmail($userFixture['email']);
            $user->setName($userFixture['name']);

            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $userFixture['password']
                )
            );
        $this->addReference('user_'.$userFixture['username'], $user);
          $manager->persist($user);
      }

        $manager->flush();
    }

    protected function getRandomUserReference(): User
    {
        return $this->getReference('user_'. self::USERS[rand(0, 3)]['username']);


        }


    }
