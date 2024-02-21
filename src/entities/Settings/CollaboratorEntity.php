<?php

declare(strict_types=1);

namespace Vertuoza\Entities\Settings;

/** Data object for collaborator representation in the API */
class CollaboratorEntity
{
    public function __construct(
        public string $name,
        public string $firstName,
        public string $id,
    ) {
    }
}
