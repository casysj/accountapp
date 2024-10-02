<?php

namespace App\Controller;

use App\Service\SettlementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/settlements')]
class SettlementController extends AbstractController
{
    private $settlementService;

    public function __construct(SettlementService $settlementService)
    {
        $this->settlementService = $settlementService;
    }

    #[Route('/{year}/{month}', methods: ['POST'])]
    public function calculateMonthlySettlement(int $year, int $month): JsonResponse
    {
        $settlements = $this->settlementService->calculateMonthlySettlement($year, $month);
        return $this->json($settlements);
    }

    #[Route('/{year}/{month}', methods: ['GET'])]
    public function getMonthlySettlement(int $year, int $month): JsonResponse
    {
        $settlements = $this->settlementService->getMonthlySettlement($year, $month);
        return $this->json($settlements);
    }
}