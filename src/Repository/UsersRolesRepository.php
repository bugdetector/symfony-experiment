<?php

namespace App\Repository;

use App\Entity\UsersRoles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UsersRoles|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersRoles|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersRoles[]    findAll()
 * @method UsersRoles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRolesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UsersRoles::class);
    }

    // /**
    //  * @return UsersRoles[] Returns an array of UsersRoles objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UsersRoles
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
