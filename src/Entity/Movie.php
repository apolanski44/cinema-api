<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $title;

    /**
     * @var Collection<int, Screening>
     */
    #[ORM\OneToMany(targetEntity: Screening::class, mappedBy: 'movie', orphanRemoval: true)]
    private Collection $screenings;

    public function __construct()
    {
        $this->screenings = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
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
            $screening->setMovie($this);
        }
    }

    public function removeScreening(Screening $screening): void
    {
        $this->screenings->removeElement($screening);
    }
}
