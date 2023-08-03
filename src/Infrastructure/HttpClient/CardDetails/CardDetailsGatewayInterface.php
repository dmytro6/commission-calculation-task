<?php

declare(strict_types=1);

namespace App\Infrastructure\HttpClient\CardDetails;

interface CardDetailsGatewayInterface
{
    public function getCardCountryCode(string $binNumber): string;
}
