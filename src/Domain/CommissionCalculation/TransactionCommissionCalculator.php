<?php

declare(strict_types=1);

namespace App\Domain\CommissionCalculation;

use App\Application\CardCountryCodeProvider;
use App\Domain\CommissionCalculation\DTO\TransactionDTO;
use App\Domain\CommissionCalculation\ValueObject\CurrencyCode;
use App\Domain\CommissionCalculation\ValueObject\EuropeanUnionCountryCode;

class TransactionCommissionCalculator
{
    private const EU_COUNTRY_MUTATOR = 0.01;

    private const NON_EU_COUNTRY_MUTATOR = 0.02;

    private const DEFAULT_CURRENCY_RATE = 1;

    public function __construct(
        private CurrencyRatesExpert $currencyRatesExpert,
        private CardCountryCodeProvider $cardCountryCodeProvider
    ) {}

    public function calculate(TransactionDTO $transaction): float
    {
        $countryMutatorValue = $this->getCountryMutatorValue($transaction);
        $fixedAmount = $this->calculateFixedAmount($transaction);

        return $fixedAmount * $countryMutatorValue;
    }

    private function getCountryMutatorValue(TransactionDTO $transaction): float
    {
        $countryCode = $this->cardCountryCodeProvider->getCardCountryCode($transaction->getBinNumber());

        try {
            EuropeanUnionCountryCode::from($countryCode);
            return self::EU_COUNTRY_MUTATOR;

        } catch (\ValueError $exception) {
            return self::NON_EU_COUNTRY_MUTATOR;
        }
    }

    private function calculateFixedAmount(TransactionDTO $transaction): float
    {
        $currencyCode = CurrencyCode::from($transaction->getCurrency());

        $currencyRate = $this->currencyRatesExpert->getCurrencyRateByCode($currencyCode);

        $currencyRate = $currencyRate ?: self::DEFAULT_CURRENCY_RATE;

        return $transaction->getAmount() / $currencyRate;
    }
}
