<?php 

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class SeatDto
{
    #[Assert\NotBlank]
    #[Assert\GreaterThan(0)]
    public int $row;
    #[Assert\NotBlank]
    #[Assert\GreaterThan(0)]
    public int $seat;
}