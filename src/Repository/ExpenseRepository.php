<?php

namespace App\Repository;

use App\Entity\Expense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Expense>
 *
 * @method Expense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Expense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Expense[]    findAll()
 * @method Expense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expense::class);
    }

    /**
     * @return Expense[] Returns an array of Expense objects
     */
    public function findByUserAndMonth(int $userId, int $year, int $month): array
    {
        $firstDayOfMonth = new \DateTime("$year-$month-01");
        $lastDayOfMonth = (clone $firstDayOfMonth)->modify('last day of this month');

        return $this->createQueryBuilder('e')
            ->andWhere('e.user = :userId')
            ->andWhere('e.date BETWEEN :firstDay AND :lastDay')
            ->setParameter('userId', $userId)
            ->setParameter('firstDay', $firstDayOfMonth)
            ->setParameter('lastDay', $lastDayOfMonth)
            ->orderBy('e.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getTotalExpenseByUserAndMonth(int $userId, int $year, int $month): float
    {
        $firstDayOfMonth = new \DateTime("$year-$month-01");
        $lastDayOfMonth = (clone $firstDayOfMonth)->modify('last day of this month');

        $result = $this->createQueryBuilder('e')
            ->select('SUM(e.amount) as total')
            ->andWhere('e.user = :userId')
            ->andWhere('e.date BETWEEN :firstDay AND :lastDay')
            ->setParameter('userId', $userId)
            ->setParameter('firstDay', $firstDayOfMonth)
            ->setParameter('lastDay', $lastDayOfMonth)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ?: 0.0;
    }
}