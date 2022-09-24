<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\VerificationRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Controller\VerificationRequestDecision;
use App\Controller\VerificationRequestUpdate;

/**
 * @Vich\Uploadable
 */
#[ApiResource(
        iri: "https://schema.org/VerificationRequest",
        normalizationContext: ['groups' => ['verification_request:read']],
        denormalizationContext: ['groups' => ['verification_request:write']],
        collectionOperations: [
            "get" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN')",
                        "security_post_denormalize_message" => "Sorry, but you have to be an admin to view verification requests."
                    ],
            "post" => [
                'input_formats' => [
                    'multipart' => ['multipart/form-data'],
                ],
            ],
        ],
        itemOperations: [
            "get" => ["security_post_denormalize" => "is_granted('ROLE_ADMIN')",
                        "security_post_denormalize_message" => "Sorry, but you have to be an admin to view verification request."
                    ],
            "patch" => [
                "security_post_denormalize" => "(object.user == user and previous_object.user == user)",
                "security_post_denormalize_message" => "Sorry, but you have to be the owner of the verification request inorder to edit it",
                "controller" => VerificationRequestUpdate::class,
            ],
            "request_decision" => [
                "security_post_denormalize" => "is_granted('ROLE_ADMIN')",
                "security_post_denormalize_message" => "Sorry, but you have to be an admin to make a decision on  verification request",
                "method" => "GET",
                "path" => "/verification_decision/{id}/{decision}/{rejection_message}",
                "controller" => VerificationRequestDecision::class,
                "read" => false,
                "openapi_context" => [
                    "parameters" => [
                        [
                            "name" => "decision",
                            "in" => "path",
                            "description" => "The decision which is an int, 1 for approve and 2 for denied",
                            "type" => "int",
                            "required" => true,
                            "example" => 1,
                            "read" => true,
                        ],
                        [
                            "name" => "rejection_message",
                            "in" => "path",
                            "description" => "The decision message which is a string",
                            "type" => "string",
                            "required" => false,
                            "example" => "Not Qualified",
                            "read" => true,
                        ]
                    ]
                ]
            ],
        ],
)]
#[ApiFilter(SearchFilter::class, properties: ['status' => 'partial', 'user.email' => 'partial'])]
#[ApiFilter(OrderFilter::class, properties: ['created_at'])]
#[ORM\Entity(repositoryClass: VerificationRequestRepository::class)]
class VerificationRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @Vich\UploadableField(mapping="verification_request", fileNameProperty="imagePath")
     */
    #[Groups(['verification_request:write'])]
    public ?File $image = null;

    #[Groups(['verification_request:write', 'verification_request:read'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $message = null;

    #[Groups(['verification_request:write', 'verification_request:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    public ?string $status = 'Verification Requested';

    #[Groups(['verification_request:write', 'verification_request:read'])]
    #[ORM\ManyToOne(inversedBy: 'verificationRequests', cascade: ['persist'])]
    #[Assert\NotBlank]
    public ?User $user = null;

    #[Groups(['verification_request:write', 'verification_request:read'])]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $created_at = null;

    #[Groups(['verification_request:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagePath = null;

    #[Groups(['verification_request:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $decisionReason = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getDecisionReason(): ?string
    {
        return $this->decisionReason;
    }

    public function setDecisionReason(?string $decisionReason): self
    {
        $this->decisionReason = $decisionReason;

        return $this;
    }
}
