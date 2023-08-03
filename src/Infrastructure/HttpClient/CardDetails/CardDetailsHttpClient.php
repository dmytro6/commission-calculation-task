<?php

declare(strict_types=1);

namespace App\Infrastructure\HttpClient\CardDetails;

use App\Infrastructure\HttpClient\Exception\ResponseParsingException;
use App\Infrastructure\HttpClient\Exception\ResponseStatusCodeException;
use App\Infrastructure\HttpClient\AbstractHttpClient;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class CardDetailsHttpClient extends AbstractHttpClient implements CardDetailsHttpClientInterface
{
    public function __construct(protected ClientInterface $client, protected string $apiUri)
    {}

    public function getCardCountryCode(string $binNumber): string
    {
        $request = $this->prepareCardDetailsRequest($binNumber);
        $response = $this->send($request);
        return $this->parseCardDetailsResponse($response);
    }

    private function prepareCardDetailsRequest(string $binNumber): RequestInterface
    {
        return new Request('GET', $this->apiUri . $binNumber);
    }

    /**
     * @param ResponseInterface $response
     * @return string
     *
     * @throws ResponseStatusCodeException
     */
    private function parseCardDetailsResponse(ResponseInterface $response): string
    {
        if ($response->getStatusCode() !== SymfonyResponse::HTTP_OK) {
            throw new ResponseStatusCodeException($response->getStatusCode());
        }

        try {
            $data = \json_decode((string) $response->getBody(), true);

            return $data['country']['alpha2'];

        } catch (\Exception $exception) {
            throw new ResponseParsingException($exception);
        }
    }
}
