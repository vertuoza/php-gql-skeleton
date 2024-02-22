<?php

declare(strict_types=1);

namespace Vertuoza\Entities\Settings;

class UnitTypeEntity
{
    public function __construct(
        public string $id = '',
        public string $name = '',
        public bool $isSystem = false,
    ) {
    }
}
