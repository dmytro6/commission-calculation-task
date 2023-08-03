<?php

declare(strict_types=1);

namespace App\Infrastructure\FileSystem;

use App\Domain\CommissionCalculation\DTO\TransactionDTO;
use App\Infrastructure\FileSystem\Exception\FileParsingException;
use App\Infrastructure\InfrastructureGatewayInterface;

class TxtAdapter implements FiletypeAdapterInterface, InfrastructureGatewayInterface
{
    public function __construct(public string $projectDir)
    {}

    /**
     * @param string $path
     * @return \Generator|null
     */
    public function getTransactionGenerator(string $path): \Generator|null
    {
        try {
            return $this->getLinesFromFile($this->projectDir . '/' . $path);
        } catch (\Exception $exception) {
            throw new FileParsingException($exception);
        }
    }

    private function getLinesFromFile($path): \Generator|null
    {
        if (!$fileHandle = fopen($path, 'r')) {
            return null;
        }

        while (false !== $line = fgets($fileHandle)) {
            yield $this->parseLine($line);
        }

        fclose($fileHandle);
    }

    private function parseLine(string $line): TransactionDTO
    {
        $transaction = \json_decode($line, true);
        return new TransactionDTO(
            (string) $transaction['bin'],
            (float) $transaction['amount'],
            (string) $transaction['currency'],
        );
    }
}
