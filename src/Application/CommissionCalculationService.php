<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\Exception\DomainException;
use App\Domain\CommissionCalculation\CommissionCalculationProcessor;

class CommissionCalculationService
{
    public function __construct(private CommissionCalculationProcessor $calculationProcessor) {}

    public function calculateFromFile(string $file): array
    {
        try {
            return $this->calculationProcessor->process($file);
        } catch (\Throwable $exception) {
            throw new DomainException($exception);
        }
    }
}
