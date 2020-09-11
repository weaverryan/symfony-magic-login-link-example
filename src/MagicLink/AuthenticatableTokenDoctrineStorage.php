<?php

namespace App\MagicLink;

use App\Entity\MagicLoginToken;
use App\Repository\MagicLoginTokenRepository;
use Doctrine\ORM\EntityManagerInterface;

class AuthenticatableTokenDoctrineStorage
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function storeToken(string $selector, string $hashedVerifier, object $user, \DateTimeInterface $expiresAt)
    {
        /** @var MagicLoginTokenRepository $repository */
        $repository = $this->entityManager->getRepository(MagicLoginToken::class);

        $magicLoginToken = new MagicLoginToken($user, $expiresAt, $selector, $hashedVerifier);
        $this->entityManager->persist($magicLoginToken);
        $this->entityManager->flush();
    }
}
