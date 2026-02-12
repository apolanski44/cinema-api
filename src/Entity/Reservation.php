<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private int $rowNumber;

    #[ORM\Column]
    private int $seatNumber;

    #[ORM\Column(length: 255)]
    private string $customerEmail;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private Screening $screening;

    public function getId(): int
    {
        return $this->id;
    }

    public function getRowNumber(): int
    {
        return $this->rowNumber;
    }

    public function setRowNumber(int $rowNumber): void
    {
        $this->rowNumber = $rowNumber;
    }

    public function getSeatNumber(): int
    {
        return $this->seatNumber;
    }

    public function setSeatNumber(int $seatNumber): void
    {
        $this->seatNumber = $seatNumber;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function setCustomerEmail(string $customerEmail): void
    {
        $this->customerEmail = $customerEmail;
    }

    public function getScreening(): Screening
    {
        return $this->screening;
    }

    public function setScreening(Screening $screening): void
    {
        $this->screening = $screening;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'customerEmail' => $this->getCustomerEmail(),
            'row' => $this->getRowNumber(),
            'seat' => $this->getSeatNumber(),
            'screening' => [
                'id' => $this->getScreening()->getId(),
                'movie' => $this->getScreening()->getMovie()->getTitle(),
                'startTime' => $this->getScreening()->getStartTime()->format('Y-m-d H:i:s'),
                'roomName' => $this->getScreening()->getRoom()->getName(),
            ]
        ];
    }
}
