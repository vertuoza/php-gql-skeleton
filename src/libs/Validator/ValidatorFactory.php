<?php

declare(strict_types=1);

namespace Vertuoza\Libs\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

/** Factory for a Symfony validator, with enabled attribute-based contraints */
class ValidatorFactory
{
    public static function build(): ValidatorInterface
    {
        return (new ValidatorBuilder())
            ->enableAttributeMapping()
            ->getValidator();
    }
}
