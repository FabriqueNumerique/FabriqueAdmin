<?php

namespace App\Repository;

use App\Entity\Retard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Retard|null find($id, $lockMode = null, $lockVersion = null)
 * @method Retard|null findOneBy(array $criteria, array $orderBy = null)
 * @method Retard[]    findAll()
 * @method Retard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RetardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Retard::class);
    }

    

    public function retardActuel()
    {
        return $this->createQueryBuilder('r')
            ->join('r.PromoAppre', 'pa')
            ->join('pa.promotion', 'p')
            ->where('p.DateFin > :date')
            ->setParameter('date', new \DateTime)
            ->getQuery()
            ->getResult();      
    }

    public function findAllByName($nom)
    {
        return $this->createQueryBuilder('r')
            ->join('r.PromoAppre', 'pa')
            ->join('pa.apprenant', 'ap')
            ->where('ap.Nom like :nom or ap.Prenom like :nom')
            ->setParameter('nom', $nom)
            ->getQuery()->getResult();
    }
}
