<?php

namespace App\Repository;

use App\Entity\MonthlySettlement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MonthlySettlement>
 *
 * @method MonthlySettlement|null find($id, $lockMode = null, $lockVersion = null)
 * @method MonthlySettlement|null findOneBy(array $criteria, array $orderBy = null)
 * @method MonthlySettlement[]    findAll()
 * @method MonthlySettlement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MonthlySettlementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MonthlySettlement::class);
    }

    /**
     * @return MonthlySettlement[] Returns an array of MonthlySettlement objects
     */
    // public function findByYearAndMonth(int $year, int $month): array
    // {
    //     return $this->createQueryBuilder('ms')
    //         ->andWhere('ms.year = :year')
    //         ->andWhere('ms.month = :month')
    //         ->setParameter('year', $year)
    //         ->setParameter('month', $month)
    //         ->orderBy('ms.totalExpense', 'DESC')
    //         ->getQuery()
    //         ->getResult();
    // }

    // public function findOneByUserAndMonth(int $userId, int $year, int $month): ?MonthlySettlement
    // {
    //     return $this->createQueryBuilder('ms')
    //         ->andWhere('ms.user = :userId')
    //         ->andWhere('ms.year = :year')
    //         ->andWhere('ms.month = :month')
    //         ->setParameter('userId', $userId)
    //         ->setParameter('year', $year)
    //         ->setParameter('month', $month)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }

    // public function getTotalExpenseForMonth(int $year, int $month): float
    // {
    //     $result = $this->createQueryBuilder('ms')
    //         ->select('SUM(ms.totalExpense) as total')
    //         ->andWhere('ms.year = :year')
    //         ->andWhere('ms.month = :month')
    //         ->setParameter('year', $year)
    //         ->setParameter('month', $month)
    //         ->getQuery()
    //         ->getSingleScalarResult();

    //     return $result ?: 0.0;
    // }
}