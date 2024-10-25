<?php

namespace Vertuoza\Exceptions;

use Throwable;
use Vertuoza\Enums\ExceptionErrorCodeEnum;

class BadRequestException extends BusinessExceptionInterface
{
    /**
     * @param string $message
     * @param ExceptionErrorCodeEnum $errorCode
     * @param Throwable|null $previous
     * @param array $args
     */
    public function __construct(
        string $message,
        ExceptionErrorCodeEnum $errorCode = ExceptionErrorCodeEnum::BAD_REQUEST,
        Throwable $previous = null,
        array $args = []
    )
    {
        parent::__construct(
            $message,
            $errorCode,
            $previous,
            $args
        );
    }
}
