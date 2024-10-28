<?php

namespace Vertuoza\Exceptions;

use Throwable;
use Vertuoza\Enums\ExceptionErrorCodeEnum;
use Vertuoza\Errors\FieldError;

// To use when the user input contains data that not allowed the process to continue. Due to business rules
class BadUserInputException extends BadRequestException
{
    /**
     * @var array<FieldError>
     */
    private array $fieldsError = [];

    /**
     * @param array|FieldError $fieldsError The fields that contains the error
     * @param string $inputName The name of the input class name
     * @param Throwable|null $previous
     * @param array $args
     */
    public function __construct(
        array|FieldError $fieldsError,
        string $inputName,
        Throwable $previous = null,
        array $args = []
    ) {
        $this->setFieldsErrors(is_array($fieldsError) ? $fieldsError : [$fieldsError]);

        parent::__construct(
            $inputName ? 'Bad user input for '.$inputName : 'Bad user input',
            ExceptionErrorCodeEnum::BAD_REQUEST,
            $previous,
            $args
        );
    }

    /**
     * @return array<FieldError>
     */
    public function getFieldsError(): array
    {
        return $this->fieldsError;
    }

    /**
     * @param FieldError ...$fieldsErrors
     *
     * @return static
     */
    public function addFieldsErrors(FieldError ...$fieldsErrors): static
    {
        foreach ($fieldsErrors as $fieldError) {
            $this->fieldsError[] = $fieldError;
        }

        return $this;
    }

    /**
     * @param array $fieldsErrors
     *
     * @return static
     */
    public function setFieldsErrors(array $fieldsErrors): static
    {
        $this->fieldsError = [];
        return $this->addFieldsErrors(...$fieldsErrors);
    }
}
