<?php

namespace Vertuoza\Validators;

use Vertuoza\Errors\FieldError;

abstract class Validator
{
    /**
     * @var string
     */
    protected string $field;

    /**
     * @var string
     */
    protected string $value;

    /**
     * @var string
     */
    protected string $path;

    /**
     * @var array<FieldError>
     */
    private array $errors = [];

    /**
     * @param string $field
     * @param string $value
     * @param string $path
     */
    public function __construct(
        string $field,
        string $value,
        string $path = ""
    ) {
        $this->field = $field;
        $this->value = $value;
        $this->path = $path;
    }

    /**
     * @return array<FieldError>
     */
    function validate(): array
    {
        return $this->errors;
    }

    /**
     * @param FieldError ...$errors
     *
     * @return static
     */
    public function addErrors(FieldError ...$errors): static
    {
        foreach ($errors as $error) {
            $this->errors[] = $error;
        }

        return $this;
    }
}
