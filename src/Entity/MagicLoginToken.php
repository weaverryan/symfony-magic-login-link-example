<?php

namespace App\Entity;

use App\Repository\MagicLoginTokenRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Security\MagicLink\MagicLoginLinkTokenEntityTrait;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\MagicLink\StoredMagicLinkTokenInterface;

/**
 * @ORM\Entity(repositoryClass=MagicLoginTokenRepository::class)
 */
class MagicLoginToken implements StoredMagicLinkTokenInterface
{
    use MagicLoginLinkTokenEntityTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
