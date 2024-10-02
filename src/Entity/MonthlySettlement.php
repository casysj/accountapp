<?php

namespace App\Entity;

use App\Repository\MonthlySettlementRepository;
use App\Trait\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MonthlySettlementRepository::class)]
#[ORM\Table(name: 'monthly_settlements')]
class MonthlySettlement
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'integer')]
    private $year;

    #[ORM\Column(type: 'integer')]
    private $month;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private $totalExpense;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private $balance;

    // Getters and setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getMonth(): ?int
    {
        return $this->month;
    }

    public function setMonth(int $month): static
    {
        $this->month = $month;

        return $this;
    }

    public function getTotalExpense(): ?string
    {
        return $this->totalExpense;
    }

    public function setTotalExpense(string $totalExpense): static
    {
        $this->totalExpense = $totalExpense;

        return $this;
    }

    public function getBalance(): ?string
    {
        return $this->balance;
    }

    public function setBalance(string $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}