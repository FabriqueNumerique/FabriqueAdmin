<?php

namespace App\Repository;

use App\Entity\Absence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Absence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Absence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Absence[]    findAll()
 * @method Absence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbsenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Absence::class);
    }

   

    public function absenceActuel()
    {
        return $this->createQueryBuilder('a')
            ->join('a.PromoAppre', 'pa')
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
