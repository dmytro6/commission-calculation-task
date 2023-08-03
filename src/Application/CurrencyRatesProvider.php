<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\Exception\InfrastructureException;
use App\Domain\CommissionCalculation\ValueObject\CurrencyCode;
use App\Infrastructure\HttpClient\CurrencyRates\CurrencyRatesGatewayInterface;

class CurrencyRatesProvider
{
    public function __construct(private CurrencyRatesGatewayInterface $apiGateway) {}

    /**
     * @return CurrencyCode[]
     */
    public function getCurrencyRates(): array
    {
        try {
            return $this->apiGateway->getCurrencyRates();
        } catch (\Throwable $exception) {
            throw new InfrastructureException($this->apiGateway, $exception);
        }
    }
}
