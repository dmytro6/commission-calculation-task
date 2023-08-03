<?php

declare(strict_types=1);

namespace App\Infrastructure\HttpClient\CurrencyRates;

interface CurrencyRatesGatewayInterface
{
    public function getCurrencyRates(): array;
}
