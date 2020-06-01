<?php

namespace App\Repository;

use App\Entity\Apprenant;

use Doctrine\ORM\QueryBuilder;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Apprenant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Apprenant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Apprenant[]    findAll()
 * @method Apprenant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApprenantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Apprenant::class);
    }


    public function findAllByNom($nom)
    {
        return $this->createQueryBuilder('a')
            ->where('a.Nom like :nom or a.Prenom like :nom')
            ->setParameter('nom', $nom)
            ->getQuery()->getResult();
    }
    
}
