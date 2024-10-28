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
    /**
     * @param string $message
     * @param $nodes
     * @param Source|null $source
     * @param array|null $positions
     * @param array|null $path
     * @param Throwable|null $previous
     * @param array|null $args
     * @param array|null $fields
     * @param ExceptionErrorCodeEnum $errorCode
     */
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

        parent::__construct(
            message: $message,
            nodes: $nodes,
            source: $source,
            positions: $positions,
            path: $path,
            previous: $previous,
            extensions: [
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
