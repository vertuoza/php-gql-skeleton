<?php

namespace Vertuoza\Exceptions;

use Exception;
use Throwable;
use Vertuoza\Enums\ExceptionErrorCodeEnum;
use Vertuoza\Interface\Exception\ProvidesExceptionArgsInterface;

class BusinessException extends Exception implements ProvidesExceptionArgsInterface
{
    /**
     * @var ExceptionErrorCodeEnum
     */
    protected ExceptionErrorCodeEnum $errorCode;

    /**
     * @var array
     */
    protected array $args;

    /**
     * @param string $message
     * @param ExceptionErrorCodeEnum $errorCode
     * @param Throwable|null $previous
     * @param array $args
     */
    public function __construct(
        string $message,
        ExceptionErrorCodeEnum $errorCode = ExceptionErrorCodeEnum::INTERNAL_SERVER_ERROR,
        Throwable $previous = null,
        array $args = []
    )
    {
        $this->args = $args;
        $this->errorCode = $errorCode;

        parent::__construct($message, $errorCode->value, $previous);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->errorCode->name}]: {$this->message}\n";
    }

    /**
     * @return ExceptionErrorCodeEnum
     */
    public function getErrorCode(): ExceptionErrorCodeEnum
    {
        return $this->errorCode;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }
}
