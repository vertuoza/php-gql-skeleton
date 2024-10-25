<?php

namespace Vertuoza\Exceptions;

use Throwable;
use Vertuoza\Enums\ExceptionErrorCodeEnum;

class NotFoundException extends BusinessExceptionInterface
{
    /**
     * @param string $message
     * @param ExceptionErrorCodeEnum $errorCode
     * @param Throwable|null $previous
     * @param array $args
     */
    public function __construct(
        string $message = "The resource requested is not available",
        ExceptionErrorCodeEnum $errorCode = ExceptionErrorCodeEnum::NOT_FOUND,
        Throwable $previous = null,
        array $args = []
    )
    {
        parent::__construct($message, $errorCode, $previous, $args);
    }
}
