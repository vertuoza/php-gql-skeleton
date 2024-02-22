<?php

declare(strict_types=1);

namespace Vertuoza\Repositories\Settings\UnitTypes;

use Symfony\Component\Validator\Constraints\Length;

class UnitTypeMutationData
{
    public function __construct(
        #[Length(min: 3, max: 255)]
        public string $name = '',
    ) {
    }
}
