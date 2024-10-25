<?php

namespace Vertuoza\Exceptions;

use Throwable;
use Vertuoza\Enums\ExceptionErrorCodeEnum;

class ForbiddenException extends BusinessExceptionInterface
{
    /**
     * @param string $message
     * @param ExceptionErrorCodeEnum $errorCode
     * @param Throwable|null $previous
     * @param array $args
     */
    public function __construct(
        string $message,
        ExceptionErrorCodeEnum $errorCode = ExceptionErrorCodeEnum::FORBIDDEN,
        Throwable $previous = null,
        array $args = []
    )
    {
        parent::__construct($message, $errorCode, $previous, $args);
    }
}
