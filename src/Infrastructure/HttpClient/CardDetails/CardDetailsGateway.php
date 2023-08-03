<?php

declare(strict_types=1);

namespace App\Infrastructure\HttpClient\CardDetails;

use App\Infrastructure\HttpClient\ApiGatewayInterface;

class CardDetailsGateway implements ApiGatewayInterface, CardDetailsGatewayInterface
{
    public function __construct(private CardDetailsHttpClient $client)
    {}

    /**
     * @param string $binNumber
     * @return string
     */
    public function getCardCountryCode(string $binNumber): string
    {
        return $this->client->getCardCountryCode($binNumber);
    }
}
