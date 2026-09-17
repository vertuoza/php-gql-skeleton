<?php

namespace Vertuoza\Entities\Organization;

readonly class CollaboratorEntity
{
    public function __construct(
        public string $id,
        public string $firstName,
        public string $name,
    ) {
    }

}