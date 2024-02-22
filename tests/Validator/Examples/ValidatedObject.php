<?php

declare(strict_types=1);

namespace Vertuoza\Tests\Validator\Examples;

use Symfony\Component\Validator\Constraints\Length;

/** Sample object with configured Symfony Validator constraints */
class ValidatedObject
{
    public function __construct(
        #[Length(min: 3, max: 255)]
        public string $name = '',
    ) {
    }
}
