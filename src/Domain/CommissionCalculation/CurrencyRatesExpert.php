<?php

declare(strict_types=1);

namespace App\Domain\CommissionCalculation;

use App\Application\CurrencyRatesProvider;
use App\Domain\CommissionCalculation\ValueObject\CurrencyCode;

class CurrencyRatesExpert
{
    private array $currencyRates;

    public function __construct(private CurrencyRatesProvider $currencyRatesProvider)
    {
        $this->currencyRates = $this->currencyRatesProvider->getCurrencyRates();
    }

    public function getCurrencyRateByCode(CurrencyCode $code): float
    {
        return (float) $this->currencyRates[$code->value];
    }
}
