<?php

declare(strict_types=1);

namespace App\Infrastructure\HttpClient\CurrencyRates;

use Psr\Http\Client\ClientInterface;

interface CurrencyRatesHttpClientInterface
{
    public function __construct(ClientInterface $client, string $apiUri, string $accessKey);

    public function getCurrencyRates(): array;
}
