<?php

namespace App\Repository;

use App\Entity\PlanDay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PlanDay|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanDay|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanDay[]    findAll()
 * @method PlanDay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanDayRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PlanDay::class);
    }

    // /**
    //  * @return PlanDay[] Returns an array of PlanDay objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlanDay
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
