<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\HttpClient\CurrencyRates;

use App\Infrastructure\HttpClient\CurrencyRates\CurrencyRatesHttpClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

class CurrencyRatesHttpClientTest extends TestCase
{
    private CurrencyRatesHttpClient $currencyRatesHttpClient;

    private ClientInterface $client;

    private const API_URI = 'test-api.uri';

    private const ACCESS_TOKEN = 'access-token';

    private const URI_NETWORKS = '/v2/networks?fields=href,location';

    /**
     * @return void
     */
    public function testGetNetworks(): void
    {
        $response = new Response(
            status: 200,
            body: fopen('data://text/plain;base64,' . base64_encode(json_encode(
                [
                    'rates' => [
                        'USD' => 1.122323,
                        'EUR' => 1,
                        'JPY' => 134.2313,
                    ],
                ]
                )),'r')
        );

        $result = [
            'USD' => 1.122323,
            'EUR' => 1,
            'JPY' => 134.2313,
        ];

        $this->client
            ->expects(static::once())
            ->method('sendRequest')
            ->with($this->callback(
                    function (Request $request) {
                      $this->assertEquals(
                          self::API_URI . '?access_key=' . self::ACCESS_TOKEN,
                          $request->getUri()->getPath() . '?' . $request->getUri()->getQuery()
                      );

                      return true;
                    }
                )
            )
            ->willReturn($response);

        $this->assertEquals($result, $this->currencyRatesHttpClient->getCurrencyRates());
    }

    protected function setUp(): void
    {
        $this->client = $this->createMock(ClientInterface::class);

        $this->currencyRatesHttpClient = new CurrencyRatesHttpClient($this->client, self::API_URI, self::ACCESS_TOKEN);
    }
}
