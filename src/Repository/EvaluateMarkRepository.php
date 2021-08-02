<?php

namespace App\Repository;

use App\Entity\EvaluateMark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EvaluateMark|null find($id, $lockMode = null, $lockVersion = null)
 * @method EvaluateMark|null findOneBy(array $criteria, array $orderBy = null)
 * @method EvaluateMark[]    findAll()
 * @method EvaluateMark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvaluateMarkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EvaluateMark::class);
    }

    // /**
    //  * @return EvaluateMark[] Returns an array of EvaluateMark objects
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
    public function findOneBySomeField($value): ?EvaluateMark
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
