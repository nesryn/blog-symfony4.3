<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ApiResource(
 *     itemOperations={"get"={
 *                          "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
 *                          "normalization_context"={
 *                            "groups"={"get"}
 *                               }
 *                          },
 *                  "put"={
 *                        "access_control"="is_granted('IS_AUTHENTICATED_FULLY') and object == user",
 *                        "denormalization_context"={
 *                            "groups"={"put"}},
 *                        "normalization_context"={
 *                            "groups"={"get"}}
 *                   }
 *      },
 *
 *     collectionOperations={
 *       "post"={
 *             "denormalization_context"={
 *                 "groups"={"post"}
 *             },
 *             "normalization_context"={
 *                 "groups"={"get"}
 *              }
 *        }
 *     },
 *
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("username", groups={"post"})
 * @UniqueEntity("email",  groups={"post"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get","post","get-comments-with-author","get-blog-post-with-author"})
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"put","post"})
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{8,}/",
     *     message="password must be 7 characters "
     * )
     */
    private $password;

    /**
     * @Groups({"put","post"})
     * @Assert\NotBlank()
     * @Assert\Expression(
     *     "this.getPassword()=== this.getRetypePassword()",
     *     message="password did not match"
     *
     * )
     */
    private $retypePassword;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get","post","put"})
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"put","post"})
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="author", orphanRemoval=true)
     * @Groups({"get"})
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=BlogPost::class, mappedBy="author", orphanRemoval=true)
     * @Groups({"get"})
     */
    private $posts;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BlogPost[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(BlogPost $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(BlogPost $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     *
     */
    public function getRoles()
    {
        return ["ROLE_USER"];
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


    public function getRetypePassword()
    {
        return $this->retypePassword;
    }


    public function setRetypePassword($retypePassword)
    {
        $this->retypePassword = $retypePassword;
    }


}
