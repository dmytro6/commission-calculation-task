<?php

declare (strict_types=1);

namespace App\Application\Exception;

use App\Infrastructure\InfrastructureGatewayInterface;

/**
 * Exception is thrown when there is an error with the infrastructure provider
 *
 */
class InfrastructureException extends \RuntimeException
{
    public function __construct(InfrastructureGatewayInterface $gateway, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                'Error while working with %s', $gateway::class
            ),
            0,
            $previous
        );
    }
}
