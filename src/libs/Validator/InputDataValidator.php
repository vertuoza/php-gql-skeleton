<?php

declare(strict_types=1);

namespace Vertuoza\Libs\Validator;

use GraphQL\Error\Error;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Service running a Symfony validator against the value. It triggers a GraphQL-compatible exception if
 * the value is invalid.
 */
class InputDataValidator
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    /** @throws Error */
    public function validate(mixed $value): void
    {
        $errors = $this->validator->validate($value);
        if (count($errors) > 0) {
            throw new Error('Input data is invalid', extensions: ['validation' => $this->describeConstraints($errors)]);
        }
    }

    /**
     * Describe the constraints in order to be used in the error parameters.
     * @param ConstraintViolationListInterface $constraints
     * @return array<array{message: string, property: string}>
     */
    private function describeConstraints(ConstraintViolationListInterface $constraints): array
    {
        $errors = [];
        foreach ($constraints as $constraint) {
            $errors[] = [
                'message' => (string)$constraint->getMessage(),
                'property' => $constraint->getPropertyPath(),
            ];
        }
        return $errors;
    }
}
