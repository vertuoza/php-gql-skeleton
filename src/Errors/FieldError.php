<?php

namespace Vertuoza\Errors;

use GraphQL\Error\Error;
use Vertuoza\Enums\FieldErrorCodeEnum;

class FieldError extends Error
{
    /**
     * @var string
     */
    public string $field;

    /**
     * @var array
     */
    public array $args;

    public function __construct(
        string $field,
        string $message,
        FieldErrorCodeEnum $codeEnum = FieldErrorCodeEnum::FIELD_ERROR,
        ?array $path = null,
        array $args = []
    ) {
        parent::__construct(
            message: $message,
            path: $path
        );

        $this->code = $codeEnum->name;
        $this->field = $field;
        $this->args = $args;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            "code" => $this->code,
            "field" => $this->field,
            "path" => $this->path,
            "message" => $this->message,
            "args" => $this->args
        ];
    }
}
