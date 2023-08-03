<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\HttpClient\CardDetails;

use App\Infrastructure\HttpClient\CardDetails\CardDetailsHttpClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

class CardDetailsHttpClientTest extends TestCase
{
    private CardDetailsHttpClient $cardDetailsHttpClient;

    private ClientInterface $client;

    private const API_URI = 'test-api.uri';

    /**
     * @return void
     */
    public function testGetNetworks(): void
    {
        $binCode = '45717360';
        $response = new Response(
            status: 200,
            body: fopen('data://text/plain;base64,' . base64_encode(json_encode(
                [
                    'country' => [
                        'alpha2' => 'DK'
                    ]
                ]
                )),'r')
        );

        $result = 'DK';

        $this->client
            ->expects(static::once())
            ->method('sendRequest')
            ->with($this->callback(
                    function (Request $request) use ($binCode){
                      $this->assertEquals(
                          self::API_URI . $binCode,
                          $request->getUri()->getPath()
                      );

                      return true;
                    }
                )
            )
            ->willReturn($response);

        $this->assertEquals($result, $this->cardDetailsHttpClient->getCardCountryCode($binCode));
    }

    protected function setUp(): void
    {
        $this->client = $this->createMock(ClientInterface::class);

        $this->cardDetailsHttpClient = new CardDetailsHttpClient($this->client, self::API_URI);
    }
}
