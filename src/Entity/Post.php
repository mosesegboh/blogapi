<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
        attributes: ["pagination_items_per_page" => 5],
        itemOperations: [
            "get",
            "put" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN') or (object.user == user and previous_object.user == user)",
                        "security_post_denormalize_message" => "Sorry, but you have to be an admin or the owner of the post to update posts"
                    ],
        ],
        collectionOperations: [
            "get",
            "post" => ["security_post_denormalize" => "is_granted('ROLE_BLOGGER') or is_granted('ROLE_ADMIN')",
                        "security_post_denormalize_message" => "Sorry, but you have to be an admin or a blogger to create post"
                    ]
        ]
)]
#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** The date the blog post is created */
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\NotNull]
    private ?\DateTimeInterface $date = null;

    /** The Title of the blog post */
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    private ?string $title = null;

    /** The Content of the blog post */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank]
    private ?string $content = null;

    /** The User that created the blog post */
    #[ORM\ManyToOne(inversedBy: 'posts', cascade: ['persist'])]
    #[Assert\NotBlank]
    public ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
