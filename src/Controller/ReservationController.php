<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\ReservationService;
use App\Form\ReservationType;
use App\DTO\ReservationDto;

#[Route('/api/reservations', name: 'api_reservations_')]
class ReservationController extends AbstractController
{
    public function __construct(
        private readonly ReservationService $reservationService
    ) {}

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = $request->getPayload()->all();
        $form = $this->createForm(ReservationType::class);
        $form->submit($data);

        if (!$form->isValid()) {
            return $this->json(['errors' => 'Invalid data'], 400);
        }

        /** @var ReservationDto $dto */
        $dto = $form->getData();

        try {
            $reservations = $this->reservationService->createReservation($dto);

            return $this->json(array_map(fn($reservation) => $reservation->toArray(), $reservations), 201);
        } catch (\LogicException $e) {
            return $this->json(['error' => $e->getMessage()], 409);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}