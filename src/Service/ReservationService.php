<?php 

namespace App\Service;

use App\DTO\ReservationDto;
use App\Repository\ReservationRepository;
use App\Repository\ScreeningRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reservation;


final class ReservationService
{
    public function __construct(private ScreeningRepository $screeningRepository, private ReservationRepository $reservationRepository, private EntityManagerInterface $em)
    {
    }

    public function createReservation(ReservationDto $dto): array
    {
        $screening    = $this->screeningRepository->find($dto->screeningId);
        $room         = $screening->getRoom();
        $reservations = [];

        if(!$screening) {
            throw new \Exception('Screening is not available');
        }

        foreach ($dto->seats as $seatData) {
            $row = $seatData->row;
            $seat = $seatData->seat;

            if($row < 1 || $row > $room->getNumberOfRows()) {
                throw new \InvalidArgumentException("Row number $row is out of bounds for room {$room->getName()}");
            }

            if($seat < 1 || $seat > $room->getSeatsPerRow()) {
                throw new \InvalidArgumentException("Seat number $seat is out of bounds for row $row in room {$room->getName()}");
            }

            if($this->reservationRepository->isSeatReserved($dto->screeningId, $row, $seat)) {
                throw new \LogicException("Seat $seat in row $row is already reserved for this screening");
            }

            $reservation = new Reservation();
            $reservation->setScreening($screening);
            $reservation->setRowNumber($row);
            $reservation->setSeatNumber($seat);
            $reservation->setCustomerEmail($dto->email);
        
            $reservations[] = $reservation;
        }

        foreach ($reservations as $res) {
            $this->em->persist($res);
        }
    
        $this->em->flush();

        return $reservations;
    }
}