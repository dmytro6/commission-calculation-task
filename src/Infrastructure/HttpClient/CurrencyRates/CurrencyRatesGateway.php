<?php

declare(strict_types=1);

namespace App\Infrastructure\HttpClient\CurrencyRates;

use App\Infrastructure\HttpClient\ApiGatewayInterface;

class CurrencyRatesGateway implements ApiGatewayInterface, CurrencyRatesGatewayInterface
{
    public function __construct(private CurrencyRatesHttpClientInterface $client)
    {}

    public function getCurrencyRates(): array
    {
        return $this->client->getCurrencyRates();
    }
}
