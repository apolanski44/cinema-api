<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Movie;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\Screening;
use App\Entity\User;
use App\Enum\UserRole;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $room = new Room();
        $room->setName('Sala 1');
        $room->setNumberOfRows(10);
        $room->setSeatsPerRow(10);
        $manager->persist($room);

        $movie = new Movie();
        $movie->setTitle('Władca Pierścieni: Drużyna Pierścienia');
        $manager->persist($movie);

        $screeningWithRes = new Screening();
        $screeningWithRes->setMovie($movie);
        $screeningWithRes->setRoom($room);
        $screeningWithRes->setStartTime(new \DateTime('2026-02-20 18:00:00'));
        $manager->persist($screeningWithRes);

        $reservation = new Reservation();
        $reservation->setScreening($screeningWithRes);
        $reservation->setRowNumber(5);
        $reservation->setSeatNumber(5);
        $reservation->setCustomerEmail('jhon@email.com');
        $manager->persist($reservation);

        $emptyScreening = new Screening();
        $emptyScreening->setMovie($movie);
        $emptyScreening->setRoom($room);
        $emptyScreening->setStartTime(new \DateTime('2026-02-20 21:00:00'));
        $manager->persist($emptyScreening);

        $user = new User();
        $user->setEmail('user@email.com');
        $user->setFirstName('Jan');
        $user->setLastName('Kowalski');
        $user->setRoles([UserRole::WORKER]);        
        $userPassword = $this->hasher->hashPassword(
            $user,
            'password'
        );
        $user->setPassword($userPassword);

        $manager->persist($user);

        $manager->flush();

    
    }
}
    