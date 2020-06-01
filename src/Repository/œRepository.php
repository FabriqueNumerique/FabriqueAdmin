<?php

namespace App\Repository;

use App\Entity\œ;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method œ|null find($id, $lockMode = null, $lockVersion = null)
 * @method œ|null findOneBy(array $criteria, array $orderBy = null)
 * @method œ[]    findAll()
 * @method œ[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class œRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, œ::class);
    }

    // /**
    //  * @return œ[] Returns an array of œ objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('�')
            ->andWhere('�.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('�.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?œ
    {
        return $this->createQueryBuilder('�')
            ->andWhere('�.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
