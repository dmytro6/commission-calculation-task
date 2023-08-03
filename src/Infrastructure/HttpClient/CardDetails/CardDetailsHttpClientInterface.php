<?php

declare(strict_types=1);

namespace App\Infrastructure\HttpClient\CardDetails;

use Psr\Http\Client\ClientInterface;

interface CardDetailsHttpClientInterface
{
    public function __construct(ClientInterface $client, string $apiUri);

    public function getCardCountryCode(string $binNumber): string;
}
