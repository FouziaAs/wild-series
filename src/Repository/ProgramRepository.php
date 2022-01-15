<?php

namespace App\Repository;

use App\Entity\Program;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Actor;


class ProgramRepository extends ServiceEntityRepository
{
    public function findLikeName(string $name)
{
    $queryBuilder = $this->createQueryBuilder('p')
        ->where('p.title LIKE :name')
        ->setParameter('name', '%' . $name . '%')
        ->orderBy('p.title', 'ASC')
        ->join('App\Entity\Actor', 'a')
        ->orWhere('a.name LIKE :name')
        ->getQuery();

    return $queryBuilder->getResult();
}
}
