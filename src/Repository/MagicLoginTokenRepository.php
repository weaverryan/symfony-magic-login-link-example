<?php

namespace App\Repository;

use App\Entity\MagicLoginToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\MagicLink\MagicLoginLinkTokenRepositoryTrait;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\MagicLink\MagicLinkTokenStorageInterface;
use Symfony\Component\Security\Http\MagicLink\StoredMagicLinkTokenInterface;

/**
 * @method MagicLoginToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method MagicLoginToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method MagicLoginToken[]    findAll()
 * @method MagicLoginToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MagicLoginTokenRepository extends ServiceEntityRepository implements MagicLinkTokenStorageInterface
{
    use MagicLoginLinkTokenRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MagicLoginToken::class);
    }

    private function createMagicToken(string $selector, string $hashedVerifier, UserInterface $user, \DateTimeInterface $expiresAt): StoredMagicLinkTokenInterface
    {
        return new MagicLoginToken(
            $user,
            $expiresAt,
            $selector,
            $hashedVerifier
        );
    }
}
