<?php

declare(strict_types=1);

namespace App\Infrastructure\HttpClient\CurrencyRates;

use App\Infrastructure\HttpClient\Exception\ResponseParsingException;
use App\Infrastructure\HttpClient\Exception\ResponseStatusCodeException;
use App\Infrastructure\HttpClient\AbstractHttpClient;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class CurrencyRatesHttpClient extends AbstractHttpClient implements CurrencyRatesHttpClientInterface
{
    public function __construct(
        protected ClientInterface $client,
        private string $apiUri,
        private string $accessKey
    ) {}

    public function getCurrencyRates(): array
    {
        $request = $this->prepareCurrencyRatesRequest();
        $response = $this->send($request);
        return $this->parseCurrencyRatesResponse($response);
    }

    private function prepareCurrencyRatesRequest(): RequestInterface
    {
        return new Request(
            method: 'GET',
            uri: $this->apiUri . '?' . http_build_query(['access_key' => $this->accessKey])
        );
    }

    /**
     * @param ResponseInterface $response
     * @return array
     *
     * @throws ResponseStatusCodeException
     */
    private function parseCurrencyRatesResponse(ResponseInterface $response): array
    {
        if ($response->getStatusCode() !== SymfonyResponse::HTTP_OK) {
            throw new ResponseStatusCodeException($response->getStatusCode());
        }

        try {
            $data = \json_decode((string) $response->getBody(), true);
            return $data['rates'];

        } catch (\Exception $exception) {
            throw new ResponseParsingException($exception);
        }
    }
}
