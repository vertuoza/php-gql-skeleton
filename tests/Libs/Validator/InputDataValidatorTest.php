<?php

declare(strict_types=1);

namespace Vertuoza\Tests\Libs\Validator;

use GraphQL\Error\Error;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Vertuoza\Libs\Validator\InputDataValidator;
use Vertuoza\Libs\Validator\ValidatorFactory;
use Vertuoza\Tests\Libs\Validator\Examples\ValidatedObject;

class InputDataValidatorTest extends TestCase
{
    /**
     * Check if default configuration of the validator & validator service works as expected
     * for a valid data input.
     * @throws Error
     */
    public function testDoesNotThrowExceptionIfInputIsValid(): void
    {
        $this->expectNotToPerformAssertions();

        $validator = new InputDataValidator(ValidatorFactory::build());
        $validator->validate(new ValidatedObject(name: 'valid name'));
    }

    /**
     * Check if default configuration of the validator & validator service works as expected
     * for a simple invalid data input that does not match the Length constraint.
     * @throws Exception
     */
    public function testThrowsExceptionIfInputIsInvalid(): void
    {
        $error = null;

        $validator = new InputDataValidator(ValidatorFactory::build());
        try {
            $validator->validate(new ValidatedObject(name: 'a'));
        } catch (Error $error) {
            $error = $error;
        }

        $this->assertInstanceOf(Error::class, $error, 'The exception was not thrown');
        $this->assertEquals('Input data is invalid', $error->getMessage());
        $this->assertIsArray($error->getExtensions(), 'The extensions are not an array');
        $this->assertEquals(
            [['message' => 'This value is too short. It should have 3 characters or more.', 'property' => 'name']],
            $error->getExtensions()['validation']
        );
    }
}
