<?php

declare(strict_types=1);

namespace Vertuoza\Repositories\Settings\Collaborators\Models;

use DateTimeInterface;

/** Model representing a row returned from the collaborator table */
class CollaboratorModel
{
    public function __construct(
        public string $id,
        public string $tenant_id,
        public string $name,
        public string $first_name,
        public ?DateTimeInterface $created_at,
        public ?DateTimeInterface $updated_at,
        public ?DateTimeInterface $deleted_at,
    ) {
    }

    public static function getPkColumnName(): string
    {
        return 'id';
    }

    public static function getTenantColumnName(): string
    {
        return 'tenant_id';
    }

    public static function getTableName(): string
    {
        return 'collaborator';
    }
}
