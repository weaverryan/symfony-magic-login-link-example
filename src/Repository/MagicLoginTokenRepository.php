<?php

namespace App\Repository;

use App\Entity\MagicLoginToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MagicLoginToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method MagicLoginToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method MagicLoginToken[]    findAll()
 * @method MagicLoginToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MagicLoginTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MagicLoginToken::class);
    }

    // /**
    //  * @return MagicLoginToken[] Returns an array of MagicLoginToken objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MagicLoginToken
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
