<?php

namespace Vertuoza\Libs\Validation;

use Ramsey\Uuid\Uuid;
use Vertuoza\Libs\Exceptions\BadUserInputException;
use Vertuoza\Libs\Exceptions\FieldError;

class InputStringValidator
{
    /**
     * Validate a string for non-empty, max length, and optional pattern.
     *
     * @param string $string The string to validate
     * @param string $fieldName Name of the field (for exception)
     * @param int $maxLength Maximum allowed length
     * @param string|null $pattern Optional regex pattern the string must match
     *
     * @throws BadUserInputException if validation fails
     */
    public static function validate(string $string, string $fieldName, int $maxLength = 255, ?string $pattern = null): void
    {
        if ($string === '') {
            throw new BadUserInputException(
                [new FieldError($fieldName, 'The field cannot be empty')],
                $fieldName
            );
        }

        if (strlen($string) > $maxLength) {
            throw new BadUserInputException(
                [new FieldError($fieldName, "The field must be less than $maxLength characters")],
                $fieldName
            );
        }

        if ($pattern !== null && !preg_match($pattern, $string)) {
            throw new BadUserInputException(
                [new FieldError($fieldName, 'The field contains invalid characters')],
                $fieldName
            );
        }
    }

    /**
     * Validate that a string is a valid UUID.
     *
     * @param string $value
     * @param string $fieldName
     *
     * @throws BadUserInputException
     */
    public static function validateUuid(string $value, string $fieldName = 'id'): void
    {
        if (Uuid::isValid($value) === false) {
            throw new BadUserInputException(
                [new FieldError($fieldName, 'Invalid UUID format')],
                $fieldName
            );
        }
    }
}