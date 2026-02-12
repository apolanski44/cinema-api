<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function isSeatReserved(int $screeningId, int $row, int $seat): bool
    {
        $qb = $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.screening = :screeningId')
            ->andWhere('r.rowNumber = :row')
            ->andWhere('r.seatNumber = :seat')
            ->setParameter('screeningId', $screeningId)
            ->setParameter('row', $row)
            ->setParameter('seat', $seat);

        return (int) $qb->getQuery()->getSingleScalarResult() > 0;
    }
}
