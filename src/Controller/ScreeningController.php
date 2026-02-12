<?php

namespace App\Controller;

use App\Entity\Screening;
use App\Repository\ScreeningRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/screenings', name: 'api_screenings_')]
class ScreeningController extends AbstractController
{
    #[Route('', methods: ['GET'], name: 'get_all')]
    public function getAll(ScreeningRepository $screeningRepository): JsonResponse
    {
        $screenings = $screeningRepository->findAllEager();
        $data = array_map(fn(Screening $screening) => $screening->toArray(), $screenings);

        return $this->json($data);
    }
}