<?php

namespace App\Controller;

use App\Service\SettlementService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('', methods: ['GET'])]
    public function getAllSettlements(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $settlements = $this->settlementService->getAllSettlements($page, $limit);
        return $this->json($settlements);
    }
}