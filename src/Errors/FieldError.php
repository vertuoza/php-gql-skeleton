<?php

namespace Vertuoza\Errors;

use Vertuoza\Enums\FieldErrorCodeEnum;

class FieldError
{
    /**
     * @var FieldErrorCodeEnum
     */
    public FieldErrorCodeEnum $code;

    /**
     * @var string
     */
    public string $field;

    /**
     * @var string
     */
    public string $path;

    /**
     * @var string
     */
    public string $message;

    /**
     * @var array
     */
    public array $args;

    public function __construct(
        string $field,
        string $message,
        FieldErrorCodeEnum $code = FieldErrorCodeEnum::FIELD_ERROR,
        string $path = "",
        array $args = []
    ) {
        $this->field = $field;
        $this->message = $message;
        $this->code = $code;
        $this->path = $path;
        $this->args = $args;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            "code" => $this->code->name,
            "field" => $this->field,
            "path" => $this->path,
            "message" => $this->message,
            "args" => $this->args
        ];
    }
}
