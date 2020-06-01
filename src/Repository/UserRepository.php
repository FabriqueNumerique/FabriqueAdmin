<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }


    public function findAllByEmail($email)
    {
        return $this->createQueryBuilder('u')
            ->where('u.email like :email')
            ->andWhere('u.roles LIKE :role1 or u.roles Like :role2 or u.roles Like :role3')
            ->setParameter('email',$email)
            ->setParameter('role1', '%' . 'ROLE_ADMIN' . '%')
            ->setParameter('role2', '%' . 'ROLE_EDITOR' . '%')
            ->setParameter('role3', '%' . 'ROLE_RESERVE' . '%')
            ->getQuery()->getResult();
    }

    public function findByRole()
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :role1 or u.roles Like :role2 or u.roles Like :role3')
            ->setParameter('role1', '%'.'ROLE_ADMIN'.'%')
            ->setParameter('role2', '%' . 'ROLE_EDITOR' . '%')
            ->setParameter('role3', '%' . 'ROLE_RESERVE' . '%')
            ->getQuery()->getResult();
    }
}

