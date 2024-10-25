<?php

namespace Vertuoza\Errors;

use GraphQL\Error\ClientAware;
use GraphQL\Error\Error;
use GraphQL\Error\ProvidesExtensions;
use GraphQL\Language\Source;
use Throwable;
use Vertuoza\Enums\ExceptionErrorCodeEnum;

class GqlClientError extends Error implements ClientAware, ProvidesExtensions
{
    public function __construct(
        string $message,
        $nodes = null,
        ?Source $source = null,
        ?array $positions = null,
        ?array $path = null,
        ?Throwable $previous = null,
        array $args = null,
        array $fields = null,
        ExceptionErrorCodeEnum $errorCode = ExceptionErrorCodeEnum::UNDEFINED
    ) {
        $args['statusCode'] = $errorCode->name;
        $args['errorCode'] = $errorCode->value;

        // make sure everything is assigned properly
        parent::__construct(
            $message,
            $nodes,
            $source,
            $positions,
            $path,
            $previous,
            [
                "code" => $errorCode->value,
                "args" => $args,
                "fields" => $fields
            ]
        );
    }

    /**
     * @return bool
     */
    public function isClientSafe(): bool
    {
        return true;
    }
}
