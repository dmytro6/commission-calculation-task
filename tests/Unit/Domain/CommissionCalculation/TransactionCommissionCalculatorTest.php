<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\CommissionCalculation;

use App\Application\CardCountryCodeProvider;
use App\Domain\CommissionCalculation\CurrencyRatesExpert;
use App\Domain\CommissionCalculation\DTO\TransactionDTO;
use App\Domain\CommissionCalculation\TransactionCommissionCalculator;
use App\Domain\CommissionCalculation\ValueObject\CurrencyCode;
use PHPUnit\Framework\TestCase;

class TransactionCommissionCalculatorTest extends TestCase
{
    private CurrencyRatesExpert $currencyRatesExpert;
    private CardCountryCodeProvider $countryCodeProvider;
    private TransactionCommissionCalculator $calculator;

    /**
     * @param TransactionDTO $transaction
     * @param string $countryCode
     * @param float $rate
     * @param float $commission
     * @return void
     *
     * @dataProvider testCalculateProvider
     */
    public function testCalculate(TransactionDTO $transaction, string $countryCode, float $rate, float $commission): void
    {
        $this->countryCodeProvider
        ->expects(static::once())
        ->method('getCardCountryCode')
        ->with($transaction->getBinNumber())
        ->willReturn($countryCode);

        $this->currencyRatesExpert
            ->expects(static::once())
            ->method('getCurrencyRateByCode')
            ->with(CurrencyCode::from($transaction->getCurrency()))
            ->willReturn($rate);

        static::assertEquals($commission, $this->calculator->calculate($transaction));
    }

    protected function testCalculateProvider(): array
    {
        return [
            'test EU with EUR' => [
                new TransactionDTO('45717360', 100, 'EUR'),
                'DK',
                1,
                1
            ],
            'test EU with USD' => [
                new TransactionDTO('516793', 50, 'USD'),
                'LT',
                1.092747,
                0.4575624549872935
            ],
            'test non EU with JPY' => [
                new TransactionDTO('45417360', 10000, 'JPY'),
                'JP',
                156.243162,
                1.2800560193475858
            ],
            'test EU with zero rate' => [
                new TransactionDTO('45717360', 100, 'EUR'),
                'PL',
                0,
                2
            ],
            'test non EU with zero rate' => [
                new TransactionDTO('45417360', 10000, 'JPY'),
                'JP',
                0,
                200.0
            ]
        ];
    }

    protected function setUp(): void
    {
        $this->currencyRatesExpert = $this->createMock(CurrencyRatesExpert::class);
        $this->countryCodeProvider = $this->createMock(CardCountryCodeProvider::class);

        $this->calculator = new TransactionCommissionCalculator($this->currencyRatesExpert, $this->countryCodeProvider);
    }
}
