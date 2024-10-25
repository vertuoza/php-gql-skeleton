<?php

namespace Vertuoza\Exceptions;

use Throwable;
use Vertuoza\Enums\ExceptionErrorCodeEnum;

class UnauthorizedTenantException extends BusinessExceptionInterface
{
    /**
     * @var string
     */
    protected string $tenantId;

    /**
     * @param string $message
     * @param string $tenantId
     * @param Throwable|null $previous
     * @param array $args
     */
    public function __construct(
        string $message,
        string $tenantId,
        Throwable $previous = null,
        array $args = []
    )
    {
        $this->tenantId = $tenantId;

        $args['tenantId'] = $tenantId;

        parent::__construct(
            $message,
            ExceptionErrorCodeEnum::UNAUTHORIZED,
            $previous,
            $args
        );
    }

    /**
     * @return string
     */
    public function getTenantId(): string
    {
        return $this->tenantId;
    }
}
