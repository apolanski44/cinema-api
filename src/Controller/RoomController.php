<?php

namespace App\Controller;

use App\DTO\RoomDto;
use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/rooms', name: 'api_rooms_')]
class RoomController extends AbstractController
{
    public function __construct(
        private readonly RoomRepository $roomRepository,
        private readonly EntityManagerInterface $em
    ) {}

    #[Route('', name: 'get_all', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $rooms = $this->roomRepository->findAll();
        $data = array_map(fn(Room $room) => $room->toArray(), $rooms);

        return $this->json($data);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = $request->getPayload()->all();
        $form = $this->createForm(RoomType::class);
        $form->submit($data);

        if (!$form->isValid()) {
            return $this->json(['errors' => 'Invalid data'], 400);
        }

        /** @var RoomDto $dto */
        $dto = $form->getData();

        $room = Room::fromDto($dto);

        $this->em->persist($room);
        $this->em->flush();

        return $this->json($room->toArray(), 201);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    #[IsGranted('ROLE_WORKER')]
    public function update(int $id, Request $request): JsonResponse
    {
        $room = $this->roomRepository->find($id);
        
        if (!$room) {
            return $this->json(['error' => 'Room not found'], 404);
        }

        $data = $request->getPayload()->all();
        $form = $this->createForm(RoomType::class);
        $form->submit($data);

        if (!$form->isValid()) {
            return $this->json(['errors' => 'Invalid data'], 400);
        }

        /** @var RoomDto $dto */
        $dto = $form->getData();

        $room->setName($dto->name);
        $room->setNumberOfRows($dto->numberOfRows);
        $room->setSeatsPerRow($dto->seatsPerRow);

        $this->em->flush();

        return $this->json($room->toArray());
    }

    #[IsGranted('ROLE_WORKER')]
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $room = $this->roomRepository->find($id);
        
        if (!$room) {
            return $this->json(['error' => 'Room not found'], 404);
        }

        try {
            $this->em->remove($room);
            $this->em->flush();

            return $this->json(['message' => 'Room deleted successfully'], 200);
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Cannot delete room'
            ], 409);
        }
    }
}
