<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\Exception\InfrastructureException;
use App\Infrastructure\FileSystem\FiletypeAdapterInterface;

class TransactionGeneratorProvider
{
    public function __construct(private FiletypeAdapterInterface $adapter) {}

    /**
     * @param string $file
     * @return \Generator|null
     */
    public function getTransactionGenerator(string $file): \Generator|null
    {
        try {
            return $this->adapter->getTransactionGenerator($file);
        } catch (\Throwable $exception) {
            throw new InfrastructureException($this->adapter, $exception);
        }
    }
}
