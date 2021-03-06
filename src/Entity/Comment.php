<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ApiResource(
 *      itemOperations={"get",
 *                      "put"={
 *                         "access_control"="is_granted('IS_AUTHENTICATED_FULLY') and object.getAuthor() == user"}
 *     },
 *
 *     collectionOperations={"get",
 *                          "post"={
 *                              "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
 *                              "normalization_context"={
 *                                  "groups"={"get-comments-with-author"}
 *                                       }
 *                                  },
 *                "api_questions_answer_get_subresource"={
 *                          "normalization_context"={"groups"={"get-comments-with-author"}}
 *                  }
 *
 *      },
 *
 *      denormalizationContext={
 *          "groups"={"post"}
 *     }
 *
 * )
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment implements AuthoredEntityInterface ,PublishedDateEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-comments-with-author"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"post","get-comments-with-author"})
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=1000)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get-comments-with-author"})
     */
    private $published;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-comments-with-author"})
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=BlogPost::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"post"})
     */
    private $blogPost;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): PublishedDateEntityInterface
    {
        $this->published = $published;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(UserInterface $author): AuthoredEntityInterface
    {
        $this->author = $author;

        return $this;
    }

    public function getBlogPost(): ?BlogPost
    {
        return $this->blogPost;
    }

    public function setBlogPost(?BlogPost $blogPost): self
    {
        $this->blogPost = $blogPost;

        return $this;
    }
}
