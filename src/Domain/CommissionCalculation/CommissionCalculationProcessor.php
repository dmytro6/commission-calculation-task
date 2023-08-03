<?php

declare(strict_types=1);

namespace App\Domain\CommissionCalculation;

use App\Application\TransactionGeneratorProvider;

class CommissionCalculationProcessor
{
    public function __construct(
        private TransactionGeneratorProvider $transactionGeneratorProvider,
        private TransactionCommissionCalculator $commissionCalculator
    ) {}

    /**
     * @param string $file
     * @return array
     */
    public function process(string $file): array
    {
        $commissionArray = [];

        $transactionGenerator = $this->transactionGeneratorProvider->getTransactionGenerator($file);

        while ($transactionGenerator->valid()) {
            $commissionArray[] = $this->commissionCalculator->calculate($transactionGenerator->current());
            $transactionGenerator->next();
        }

        return $commissionArray;
    }
}
