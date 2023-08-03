<?php

declare(strict_types=1);

namespace App\Infrastructure\FileSystem;

interface FiletypeAdapterInterface
{
    public function __construct(
        string $projectDir
    );

    /**
     * @param string $path
     * @return \Generator|null
     */
    public function getTransactionGenerator(string $path): \Generator|null;
}
