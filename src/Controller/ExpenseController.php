<?php

namespace App\Controller;

use App\Entity\Expense;
use App\Service\ExpenseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/expenses')]
class ExpenseController extends AbstractController
{
    private $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    #[Route('', methods: ['GET'])]
    public function listExpense(Request $request) : JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        
        $user = $this->getUser();
        $expenses = $this->expenseService->getAllExpenses($user, $page, $limit);
        return $this->json($expenses, Response::HTTP_OK);
    }

    #[Route('', methods: ['POST'])]
    public function addExpense(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser(); // Assuming you have user authentication set up

        $expense = $this->expenseService->addExpense(
            $user,
            $data['amount'],
            new \DateTime($data['date']),
            $data['description'] ?? null
        );

        return $this->json($expense, Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function updateExpense(Request $request, Expense $expense): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $updatedExpense = $this->expenseService->updateExpense(
            $expense,
            $data['amount'],
            new \DateTime($data['date']),
            $data['description'] ?? null
        );

        return $this->json($updatedExpense);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function deleteExpense(Expense $expense): JsonResponse
    {
        $this->expenseService->deleteExpense($expense);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/user/{year}/{month}', methods: ['GET'])]
    public function getUserMonthlyExpenses(int $year, int $month): JsonResponse
    {
        $user = $this->getUser();
        $expenses = $this->expenseService->getUserMonthlyExpenses($user, $year, $month);
        return $this->json($expenses);
    }

    #[Route('/user/{year}/{month}/total', methods: ['GET'])]
    public function getUserMonthlyTotal(int $year, int $month): JsonResponse
    {
        $user = $this->getUser();
        $total = $this->expenseService->calculateUserMonthlyTotal($user, $year, $month);
        return $this->json(['total' => $total]);
    }
}