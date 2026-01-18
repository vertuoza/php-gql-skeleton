<?php

namespace Vertuoza\Entities\Settings;

class CollaboratorEntity
{
    public function __construct(
        public string|int $id,
        public string $name,
        public string $firstName
    ) {}
}