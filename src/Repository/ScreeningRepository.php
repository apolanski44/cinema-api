<?php

namespace App\Repository;

use App\Entity\Screening;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Screening>
 */
class ScreeningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Screening::class);
    }

    public function findAllEager(): array
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.room', 'r')
            ->addSelect('r')
            ->innerJoin('s.movie', 'm')
            ->addSelect('m')
            ->leftJoin('s.reservations', 'res')
            ->addSelect('res')
            ->getQuery()
            ->getResult();
    }
}
