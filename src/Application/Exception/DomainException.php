<?php

declare (strict_types=1);

namespace App\Application\Exception;

use App\Infrastructure\InfrastructureGatewayInterface;

/**
 * Exception is thrown when there is an error with the infrastructure provider
 *
 */
class DomainException extends \RuntimeException
{
    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(
            'Domain logic exception',
            0,
            $previous
        );
    }
}
