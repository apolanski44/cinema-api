<?php

namespace App\Tests\Units\Service;

use App\DTO\ReservationDto;
use App\DTO\SeatDto;
use App\Entity\Screening;
use App\Entity\Room;
use PHPUnit\Framework\TestCase;
use App\Repository\ScreeningRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ReservationService;
use App\Entity\Reservation;


class ReservationServiceTest extends TestCase
{
   public function testCreateReservationThrowsExceptionWhenScreeningNotFound(): void
   {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Screening is not available');

        $screeningRepository   = $this->createMock(ScreeningRepository::class);
        $reservationRepository = $this->createMock(ReservationRepository::class);
        $entityManager         = $this->createMock(EntityManagerInterface::class);

        $screeningRepository
            ->expects($this->once())
            ->method('find')
            ->with(999)
            ->willReturn(null);

        $service = new ReservationService($screeningRepository, $reservationRepository, $entityManager);

        $dto = new ReservationDto();
        $dto->screeningId = 999;
        $dto->seats = [];
        $dto->email = 'test@example.com';

        $service->createReservation($dto);
   }

   public function testThrowsExceptionWhenRowIsLowerThanOne(): void
   {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Row number 0 is out of bounds for room Test Room');

        $room                  = $this->createMock(Room::class);
        $screening             = $this->createMock(Screening::class);
        $screeningRepository   = $this->createMock(ScreeningRepository::class);
        $reservationRepository = $this->createMock(ReservationRepository::class);
        $entityManager         = $this->createMock(EntityManagerInterface::class);

        $room
            ->method('getNumberOfRows')
            ->willReturn(10);

        $room
            ->method('getName')
            ->willReturn('Test Room');

        $screening
            ->method('getRoom')
            ->willReturn($room);

        $screeningRepository
            ->method('find')
            ->willReturn($screening);

        $service = new ReservationService($screeningRepository, $reservationRepository, $entityManager);

        $seatDto = new SeatDto();
        $seatDto->row = 0;
        $seatDto->seat = 5;

        $dto = new ReservationDto();
        $dto->screeningId = 1;
        $dto->seats = [$seatDto];
        $dto->email = 'test@example.com';

        $service->createReservation($dto);
   }

   public function testThrowsExceptionWhenRowIsGreaterThanMaxRows(): void
   {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Row number 11 is out of bounds for room Test Room');

        $room                  = $this->createMock(Room::class);
        $screening             = $this->createMock(Screening::class);
        $screeningRepository   = $this->createMock(ScreeningRepository::class);
        $reservationRepository = $this->createMock(ReservationRepository::class);
        $entityManager         = $this->createMock(EntityManagerInterface::class);

        $room
            ->method('getNumberOfRows')
            ->willReturn(10);

        $room
            ->method('getName')
            ->willReturn('Test Room');

        $screening
            ->method('getRoom')
            ->willReturn($room);

        $screeningRepository
            ->method('find')
            ->willReturn($screening);

        $service = new ReservationService($screeningRepository, $reservationRepository, $entityManager);

        $seatDto = new SeatDto();
        $seatDto->row = 11;
        $seatDto->seat = 5;

        $dto = new ReservationDto();
        $dto->screeningId = 1;
        $dto->seats = [$seatDto];
        $dto->email = 'test@example.com';

        $service->createReservation($dto);
   }

   public function testThrowsExceptionWhenSeatIsLowerThanOne(): void
   {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Seat number 0 is out of bounds for row 5 in room Test Room');

        $room                  = $this->createMock(Room::class);
        $screening             = $this->createMock(Screening::class);
        $screeningRepository   = $this->createMock(ScreeningRepository::class);
        $reservationRepository = $this->createMock(ReservationRepository::class);
        $entityManager         = $this->createMock(EntityManagerInterface::class);

        $room
            ->method('getNumberOfRows')
            ->willReturn(10);

        $room
            ->method('getSeatsPerRow')
            ->willReturn(12);

        $room
            ->method('getName')
            ->willReturn('Test Room');

        $screening
            ->method('getRoom')
            ->willReturn($room);

        $screeningRepository
            ->method('find')
            ->willReturn($screening);

        $service = new ReservationService($screeningRepository, $reservationRepository, $entityManager);

        $seatDto = new SeatDto();
        $seatDto->row = 5;
        $seatDto->seat = 0;

        $dto = new ReservationDto();
        $dto->screeningId = 1;
        $dto->seats = [$seatDto];
        $dto->email = 'test@example.com';

        $service->createReservation($dto);
   }

   public function testThrowsExceptionWhenSeatIsGreaterThanMaxSeats(): void
   {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Seat number 13 is out of bounds for row 5 in room Test Room');

        $room                  = $this->createMock(Room::class);
        $screening             = $this->createMock(Screening::class);
        $screeningRepository   = $this->createMock(ScreeningRepository::class);
        $reservationRepository = $this->createMock(ReservationRepository::class);
        $entityManager         = $this->createMock(EntityManagerInterface::class);

        $room
            ->method('getNumberOfRows')
            ->willReturn(10);

        $room
            ->method('getSeatsPerRow')
            ->willReturn(12);

        $room
            ->method('getName')
            ->willReturn('Test Room');

        $screening
            ->method('getRoom')
            ->willReturn($room);

        $screeningRepository
            ->method('find')
            ->willReturn($screening);

        $service = new ReservationService($screeningRepository, $reservationRepository, $entityManager);

        $seatDto = new SeatDto();
        $seatDto->row = 5;
        $seatDto->seat = 13;

        $dto = new ReservationDto();
        $dto->screeningId = 1;
        $dto->seats = [$seatDto];
        $dto->email = 'test@example.com';

        $service->createReservation($dto);
   }

   public function testThrowsExceptionWhenSeatIsAlreadyReserved(): void
   {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Seat 5 in row 3 is already reserved for this screening');

        $room                  = $this->createMock(Room::class);
        $screening             = $this->createMock(Screening::class);
        $screeningRepository   = $this->createMock(ScreeningRepository::class);
        $reservationRepository = $this->createMock(ReservationRepository::class);
        $entityManager         = $this->createMock(EntityManagerInterface::class);

        $room
            ->method('getNumberOfRows')
            ->willReturn(10);

        $room
            ->method('getSeatsPerRow')
            ->willReturn(12);

        $room
            ->method('getName')
            ->willReturn('Test Room');

        $screening
            ->method('getRoom')
            ->willReturn($room);

        $screeningRepository
            ->method('find')
            ->willReturn($screening);

        $reservationRepository
            ->method('isSeatReserved')
            ->willReturn(true);

        $service = new ReservationService($screeningRepository, $reservationRepository, $entityManager);

        $seatDto = new SeatDto();
        $seatDto->row = 3;
        $seatDto->seat = 5;

        $dto = new ReservationDto();
        $dto->screeningId = 1;
        $dto->seats = [$seatDto];
        $dto->email = 'test@example.com';

        $service->createReservation($dto);
   }

   public function testCreateReservationSuccessfully(): void
   {
        $room                  = $this->createMock(Room::class);
        $screening             = $this->createMock(Screening::class);
        $screeningRepository   = $this->createMock(ScreeningRepository::class);
        $reservationRepository = $this->createMock(ReservationRepository::class);
        $entityManager         = $this->createMock(EntityManagerInterface::class);

        $room
            ->method('getNumberOfRows')
            ->willReturn(10);

        $room
            ->method('getSeatsPerRow')
            ->willReturn(12);

        $room
            ->method('getName')
            ->willReturn('Test Room');

        $screening
            ->method('getRoom')
            ->willReturn($room);

        $screeningRepository
            ->method('find')
            ->willReturn($screening);

        $reservationRepository
            ->method('isSeatReserved')
            ->willReturn(false);

        $entityManager
            ->expects($this->exactly(2))
            ->method('persist');

        $entityManager
            ->expects($this->once())
            ->method('flush');

        $service = new ReservationService($screeningRepository, $reservationRepository, $entityManager);

        $seatDto1 = new SeatDto();
        $seatDto1->row = 3;
        $seatDto1->seat = 5;

        $seatDto2 = new SeatDto();
        $seatDto2->row = 3;
        $seatDto2->seat = 6;

        $dto = new ReservationDto();
        $dto->screeningId = 1;
        $dto->seats = [$seatDto1, $seatDto2];
        $dto->email = 'test@example.com';

        $result = $service->createReservation($dto);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(Reservation::class, $result);
        
        $this->assertEquals(3, $result[0]->getRowNumber());
        $this->assertEquals(5, $result[0]->getSeatNumber());
        $this->assertEquals('test@example.com', $result[0]->getCustomerEmail());
        
        $this->assertEquals(3, $result[1]->getRowNumber());
        $this->assertEquals(6, $result[1]->getSeatNumber());
        $this->assertEquals('test@example.com', $result[1]->getCustomerEmail());
   }
}
