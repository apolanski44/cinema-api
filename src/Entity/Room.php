<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column]
    private int $numberOfRows;

    #[ORM\Column]
    private int $seatsPerRow;

    /**
     * @var Collection<int, Screening>
     */
    #[ORM\OneToMany(targetEntity: Screening::class, mappedBy: 'room', orphanRemoval: true)]
    private Collection $screenings;

    public function __construct()
    {
        $this->screenings = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getNumberOfRows(): int
    {
        return $this->numberOfRows;
    }

    public function setNumberOfRows(int $numberOfRows): void
    {
        $this->numberOfRows = $numberOfRows;
    }

    public function getSeatsPerRow(): int
    {
        return $this->seatsPerRow;
    }

    public function setSeatsPerRow(int $seatsPerRow): void
    {
        $this->seatsPerRow = $seatsPerRow;
    }

    /**
     * @return Collection<int, Screening>
     */
    public function getScreenings(): Collection
    {
        return $this->screenings;
    }

    public function addScreening(Screening $screening): void
    {
        if (!$this->screenings->contains($screening)) {
            $this->screenings->add($screening);
            $screening->setRoom($this);
        }
    }

    public function removeScreening(Screening $screening): void
    {
        $this->screenings->removeElement($screening);
    }
}
