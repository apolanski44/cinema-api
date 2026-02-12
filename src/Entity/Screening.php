<?php

namespace App\Entity;

use App\Repository\ScreeningRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScreeningRepository::class)]
class Screening
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private \DateTime $startTime;

    #[ORM\ManyToOne(inversedBy: 'screenings')]
    #[ORM\JoinColumn(nullable: false)]
    private Room $room;

    #[ORM\ManyToOne(inversedBy: 'screenings')]
    #[ORM\JoinColumn(nullable: false)]
    private Movie $movie;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'screening', orphanRemoval: true)]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTime $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function getRoom(): Room
    {
        return $this->room;
    }

    public function setRoom(Room $room): void
    {
        $this->room = $room;
    }

    public function getMovie(): Movie
    {
        return $this->movie;
    }

    public function setMovie(Movie $movie): void
    {
        $this->movie = $movie;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): void
    {
        $this->reservations->contains($reservation);
    }

    public function removeReservation(Reservation $reservation): void
    {
        $this->reservations->removeElement($reservation);
    }

    public function toArray(): array
{
    return [
        'id' => $this->getId(),
        'movie' => $this->getMovie()->getTitle(),
        'startTime' => $this->getStartTime()->format('Y-m-d H:i:s'),
        'room' => [
            'name' => $this->getRoom()->getName(),
            'rows' => $this->getRoom()->getNumberOfRows(),
            'seatsPerRow' => $this->getRoom()->getSeatsPerRow(),
        ],
        'occupiedSeats' => array_map(fn($res) => [
            'row' => $res->getRowNumber(),
            'seat' => $res->getSeatNumber()
        ], $this->getReservations()->toArray())
    ];
}
}
