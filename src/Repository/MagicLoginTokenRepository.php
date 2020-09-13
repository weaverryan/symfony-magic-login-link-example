<?php

namespace App\Repository;

use App\Entity\MagicLoginToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MagicLoginToken::class);
    }

    public function storeToken(string $selector, string $hashedVerifier, UserInterface $user, \DateTimeInterface $expiresAt): void
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

    public function findToken(string $selector): ?StoredMagicLinkTokenInterface
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

    public function removeExpiredTokens(): void
    {
        // keep very-recently expired tokens so that you
        // can show "token is expired" message if desired
        $time = new \DateTimeImmutable('-1 day');
        $query = $this->createQueryBuilder('mlt')
            ->delete()
            ->where('mlt.expiresAt <= :time')
            ->setParameter('time', $time)
            ->getQuery()
        ;

        $query->execute();
    }
}
