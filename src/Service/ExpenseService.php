<?php

namespace App\Service;

use App\Entity\Expense;
use App\Entity\User;
use App\Repository\ExpenseRepository;
use Doctrine\ORM\EntityManagerInterface;

class ExpenseService
{
    private $entityManager;
    private $expenseRepository;

    public function __construct(EntityManagerInterface $entityManager, ExpenseRepository $expenseRepository)
    {
        $this->entityManager = $entityManager;
        $this->expenseRepository = $expenseRepository;
    }

    public function addExpense(User $user, float $amount, \DateTime $date, ?string $description): Expense
    {
        $expense = new Expense();
        $expense->setUser($user);
        $expense->setAmount($amount);
        $expense->setDate($date);
        $expense->setDescription($description);

        $this->entityManager->persist($expense);
        $this->entityManager->flush();

        return $expense;
    }

    public function getUserMonthlyExpenses(User $user, int $year, int $month): array
    {
        return $this->expenseRepository->findByUserAndMonth($user->getId(), $year, $month);
    }

    public function calculateUserMonthlyTotal(User $user, int $year, int $month): float
    {
        return $this->expenseRepository->getTotalExpenseByUserAndMonth($user->getId(), $year, $month);
    }

    public function updateExpense(Expense $expense, float $amount, \DateTime $date, ?string $description): Expense
    {
        $expense->setAmount($amount);
        $expense->setDate($date);
        $expense->setDescription($description);

        $this->entityManager->flush();

        return $expense;
    }

    public function deleteExpense(Expense $expense): void
    {
        $this->entityManager->remove($expense);
        $this->entityManager->flush();
    }
}