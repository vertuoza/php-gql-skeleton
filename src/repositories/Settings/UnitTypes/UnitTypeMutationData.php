<?php

declare(strict_types=1);

namespace Vertuoza\Repositories\Settings\UnitTypes;

class UnitTypeMutationData
{
    public function __construct(
        public string $name = '',
    ) {
    }
}
