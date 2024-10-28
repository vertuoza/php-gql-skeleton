<?php

namespace Vertuoza\Validators;

use Vertuoza\Enums\FieldErrorCodeEnum;
use Vertuoza\Errors\FieldError;

class StringValidator extends Validator
{
    /**
     * @param string $field
     * @param string $value
     * @param string $path
     */
    public function __construct(string $field, string $value, string $path = "")
    {
        parent::__construct($field, $value, $path);
    }

    /**
     * @param bool $trimmed
     *
     * @return static
     */
    public function notEmpty(bool $trimmed = false): static
    {
        $value = $trimmed ? trim($this->value) : $this->value;
        if (empty($value)) {
            $this->addErrors(
                new FieldError(
                    $this->field,
                    'Field cannot be empty',
                    FieldErrorCodeEnum::EMPTY,
                    $this->path
                )
            );
        }

        return $this;
    }

    /**
     * @param int $max
     *
     * @return static
     */
    public function max(int $max): static
    {
        if (isset($this->value) && strlen($this->value) > $max) {
            $this->addErrors(
                new FieldError(
                    $this->field,
                    'Field cannot be longer than '.$max.' characters',
                    FieldErrorCodeEnum::MAX_LENGTH,
                    $this->path,
                    ['max' => $max]
                )
            );
        }

        return $this;
    }

    /**
     * @param int $min
     *
     * @return static
     */
    public function min(int $min): static
    {
        if (isset($value) && strlen($this->value) > $min) {
            $this->addErrors(
                new FieldError(
                    $this->field,
                    'Field cannot be less than '.$min.' characters',
                    FieldErrorCodeEnum::MIN_LENGTH,
                    $this->path,
                    ['min' => $min]
                )
            );
        }

        return $this;
    }
}
