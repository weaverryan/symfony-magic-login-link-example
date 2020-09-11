<?php

namespace App\Repository;

use App\Entity\MagicLoginToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\AuthenticatableToken\AuthenticatableTokenInterface;
use Symfony\Component\Security\Core\AuthenticatableToken\AuthenticatableTokenStorageInterface;

/**
 * @method MagicLoginToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method MagicLoginToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method MagicLoginToken[]    findAll()
 * @method MagicLoginToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MagicLoginTokenRepository extends ServiceEntityRepository implements AuthenticatableTokenStorageInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MagicLoginToken::class);
    }

    public function storeToken(string $selector, string $hashedVerifier, object $user, \DateTimeInterface $expiresAt): void
    {
        $magicLoginToken = new MagicLoginToken(
            $user,
            $expiresAt,
            $selector,
            $hashedVerifier
        );
        $this->getEntityManager()->persist($magicLoginToken);
        $this->getEntityManager()->flush();
    }

    public function findToken(string $selector): ?AuthenticatableTokenInterface
    {
        return $this->findOneBy(['selector' => $selector]);
    }

    public function invalidateToken(string $selector): void
    {
        $this->createQueryBuilder('mlt')
            ->delete()
            ->where('mlt.selector = :selector')
            ->setParameter('selector', $selector)
            ->getQuery()
            ->execute();
    }
}
