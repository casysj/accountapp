<?php

namespace App\Service;

use App\Entity\MonthlySettlement;
use App\Entity\User;
use App\Repository\ExpenseRepository;
use App\Repository\MonthlySettlementRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class SettlementService
{
    private $entityManager;
    private $expenseRepository;
    private $monthlySettlementRepository;
    private $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ExpenseRepository $expenseRepository,
        MonthlySettlementRepository $monthlySettlementRepository,
        UserRepository $userRepository
    ) {
        $this->entityManager = $entityManager;
        $this->expenseRepository = $expenseRepository;
        $this->monthlySettlementRepository = $monthlySettlementRepository;
        $this->userRepository = $userRepository;
    }

    public function calculateMonthlySettlement(int $year, int $month): array
    {
        $users = $this->userRepository->findAll();
        $totalExpense = 0;
        $userExpenses = [];

        foreach ($users as $user) {
            $expense = $this->expenseRepository->getTotalExpenseByUserAndMonth($user->getId(), $year, $month);
            $userExpenses[$user->getId()] = $expense;
            $totalExpense += $expense;
        }

        $averageExpense = $totalExpense / count($users);
        $settlements = [];

        foreach ($users as $user) {
            $balance = $averageExpense - $userExpenses[$user->getId()];
            $balance = round($balance, 2, PHP_ROUND_HALF_UP);
            $settlement = $this->createOrUpdateSettlement($user, $year, $month, $userExpenses[$user->getId()], $balance);
            $settlements[] = $settlement;
        }

        $this->entityManager->flush();

        return $settlements;
    }

    private function createOrUpdateSettlement(User $user, int $year, int $month, float $totalExpense, float $balance): MonthlySettlement
    {
        $settlement = $this->monthlySettlementRepository->findOneByUserAndMonth($user->getId(), $year, $month);

        if (!$settlement) {
            $settlement = new MonthlySettlement();
            $settlement->setUser($user);
            $settlement->setYear($year);
            $settlement->setMonth($month);
        }

        $settlement->setTotalExpense($totalExpense);
        $settlement->setBalance($balance);

        $this->entityManager->persist($settlement);

        return $settlement;
    }

    public function getMonthlySettlement(int $year, int $month): array
    {
        return $this->monthlySettlementRepository->findByYearAndMonth($year, $month);
    }

    public function getAllSettlements(int $page = 1, int $limit = 10, User $user): array
    {
        $query = $this->entityManager->createQuery(
            'SELECT ms
            FROM App\Entity\MonthlySettlement ms
            WHERE ms.user = :user
            ORDER BY ms.year DESC, ms.month DESC'
        )
        ->setParameter('user', $user)
        ->setFirstResult(($page - 1) * $limit)
        ->setMaxResults($limit);

        $paginator = new Paginator($query, $fetchJoinCollection = false);
        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / $limit);

        $settlements = iterator_to_array($paginator);

        return [
            'items' => $settlements,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $limit
        ];
    }
}