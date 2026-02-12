<?php 

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ReservationDto
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public int $screeningId;

    /** @var SeatDto[] */
    #[Assert\NotBlank]
    #[Assert\Type('array')]
    public array $seats;

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;
}