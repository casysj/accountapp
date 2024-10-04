<?php

namespace App\Service;

use App\Entity\Expense;
use App\Entity\User;
use App\Repository\ExpenseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ExpenseService
{
    private $entityManager;
    private $expenseRepository;

    public function __construct(EntityManagerInterface $entityManager, ExpenseRepository $expenseRepository)
    {
        $this->entityManager = $entityManager;
        $this->expenseRepository = $expenseRepository;
    }

    public function getAllExpenses(User $user,int $page = 1, int $limit = 10): array
    {
        $query = $this->entityManager->createQuery(
            'SELECT e
            FROM App\Entity\Expense e
            WHERE e.user = :user
            ORDER BY e.createdAt DESC'
        )
        ->setParameter('user', $user)
        ->setFirstResult(($page - 1) * $limit)
        ->setMaxResults($limit);

        $paginator = new Paginator($query, $fetchJoinCollection = false);
        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / $limit);

        $expenses = iterator_to_array($paginator);

        return [
            'items' => $expenses,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $limit
        ];
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