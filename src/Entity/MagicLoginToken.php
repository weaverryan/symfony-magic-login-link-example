<?php

namespace App\Entity;

use App\Repository\MagicLoginTokenRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MagicLoginTokenRepository::class)
 */
class MagicLoginToken
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $selector;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $hashedVerifier;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $expiresAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $user;

    public function __construct(User $user, \DateTimeInterface $expiresAt, string $selector, string $hashedVerifier)
    {
        $this->user = $user;

        $this->initialize($expiresAt, $selector, $hashedVerifier);
    }

    private function initialize(\DateTimeInterface $expiresAt, string $selector, string $hashedToken)
    {
        $this->createdAt = new \DateTimeImmutable('now');
        $this->expiresAt = $expiresAt;
        $this->selector = $selector;
        $this->hashedVerifier = $hashedToken;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function isExpired(): bool
    {
        return $this->expiresAt->getTimestamp() <= \time();
    }

    public function getExpiresAt(): \DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function getHashedVerifier(): string
    {
        return $this->hashedVerifier;
    }

    public function getId(): ?int
    {
        return $this->id;
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