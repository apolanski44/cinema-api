<?php

namespace App\Tests\Units\Service;

use App\DTO\ReservationDto;
use PHPUnit\Framework\TestCase;
use App\Repository\ScreeningRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ReservationService;


class ReservationServiceTest extends TestCase
{
   public function testCreateReservationThrowsExceptionWhenScreeningNotFound(): void
   {
       $this->expectException(\Exception::class);
       $this->expectExceptionMessage('Screening is not available');

       $screeningRepository = $this->createMock(ScreeningRepository::class);
       $reservationRepository = $this->createMock(ReservationRepository::class);
       $entityManager = $this->createMock(EntityManagerInterface::class);

       $screeningRepository->method('find')->willReturn(null);

       $service = new ReservationService($screeningRepository, $reservationRepository, $entityManager);

       $dto = new ReservationDto();
       $dto->screeningId = 999; // Non-existent screening ID
       $dto->seats = [];
       $dto->email = 'test@example.com';
   }
}