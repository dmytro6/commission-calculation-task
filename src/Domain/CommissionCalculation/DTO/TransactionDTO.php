<?php

declare(strict_types=1);

namespace App\Domain\CommissionCalculation\DTO;

readonly class TransactionDTO
{
    public function __construct(
        private string $binNumber,
        private float $amount,
        private string $currency
    ) {}

    /**
     * @return string
     */
    public function getBinNumber(): string
    {
        return $this->binNumber;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
}
