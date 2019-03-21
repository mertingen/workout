<?php

namespace App\Repository;

use App\Entity\ExerciseInstance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExerciseInstance|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExerciseInstance|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExerciseInstance[]    findAll()
 * @method ExerciseInstance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExerciseInstanceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExerciseInstance::class);
    }

    // /**
    //  * @return ExerciseInstance[] Returns an array of ExerciseInstance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExerciseInstance
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
