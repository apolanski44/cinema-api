<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RoomDto
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(min: 1, max: 255)]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    #[Assert\GreaterThan(0)]
    public int $numberOfRows;

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    #[Assert\GreaterThan(0)]
    public int $seatsPerRow;
}
