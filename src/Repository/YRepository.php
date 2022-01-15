<?php

namespace App\Repository;

use App\Entity\Y;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Y|null find($id, $lockMode = null, $lockVersion = null)
 * @method Y|null findOneBy(array $criteria, array $orderBy = null)
 * @method Y[]    findAll()
 * @method Y[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Y::class);
    }

    // /**
    //  * @return Y[] Returns an array of Y objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('y.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Y
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
