<?php

namespace App\Service;

use App\Entity\MonthlySettlement;
use App\Entity\User;
use App\Repository\ExpenseRepository;
use App\Repository\MonthlySettlementRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

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
}