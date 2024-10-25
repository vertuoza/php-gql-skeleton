<?php

namespace Vertuoza\Exceptions;

use Throwable;
use Vertuoza\Enums\ExceptionErrorCodeEnum;

class NotAuthorizedException extends BusinessExceptionInterface
{
    /**
     * @param Throwable|null $previous
     * @param array $args
     */
    public function __construct(
        Throwable $previous = null,
        array $args = []
    )
    {
        parent::__construct(
            "You are not authenticated",
            ExceptionErrorCodeEnum::UNAUTHORIZED,
            $previous,
            $args
        );
    }
}
