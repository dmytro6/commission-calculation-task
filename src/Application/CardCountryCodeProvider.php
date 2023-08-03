<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\Exception\InfrastructureException;
use App\Infrastructure\HttpClient\CardDetails\CardDetailsGatewayInterface;

class CardCountryCodeProvider
{
    public function __construct(private CardDetailsGatewayInterface $apiGateway) {}

    public function getCardCountryCode(string $binNumber): string
    {
        try {
            return $this->apiGateway->getCardCountryCode($binNumber);
        } catch (\Throwable $exception) {
            throw new InfrastructureException($this->apiGateway, $exception);
        }
    }
}
