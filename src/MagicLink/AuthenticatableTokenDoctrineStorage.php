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

    public function storeToken(AuthenticatableToken $authenticatableToken)
    {
        /** @var MagicLoginTokenRepository $repository */
        $repository = $this->entityManager->getRepository(MagicLoginToken::class);

        $magicLoginToken = new MagicLoginToken(
            $authenticatableToken->getUser(),
            $authenticatableToken->getExpiresAt(),
            $authenticatableToken->getSelector(),
            $authenticatableToken->getHashedVerifier()
        );
        $this->entityManager->persist($magicLoginToken);
        $this->entityManager->flush();
    }

    public function findToken(string $selector): ?AuthenticatableToken
    {
        /** @var MagicLoginTokenRepository $repository */
        $repository = $this->entityManager->getRepository(MagicLoginToken::class);

        /** @var MagicLoginToken $magicLoginToken */
        $magicLoginToken = $repository->findOneBy(['selector' => $selector]);

        if (!$magicLoginToken) {
            return null;
        }

        return new AuthenticatableToken(
            $magicLoginToken->getSelector(),
            $magicLoginToken->getHashedVerifier(),
            $magicLoginToken->getUser(),
            $magicLoginToken->getExpiresAt()
        );
    }

    public function invalidateToken(string $selector): void
    {
        /** @var MagicLoginTokenRepository $repository */
        $repository = $this->entityManager->getRepository(MagicLoginToken::class);

        $repository->createQueryBuilder('mlt')
            ->delete()
            ->where('mlt.selector = :selector')
            ->setParameter('selector', $selector)
            ->getQuery()
            ->execute();
    }
}
