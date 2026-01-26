<?php

namespace Vertuoza\Repositories\Settings\CollaboratorTypes\Models;

use Vertuoza\Entities\Settings\CollaboratorTypeEntity;

class CollaboratorTypeMapper
{
    public static function modelToEntity(CollaboratorTypeModel $dbData): CollaboratorTypeEntity
    {
        $entity = new CollaboratorTypeEntity();
        $entity->id = $dbData->id;
        $entity->name = $dbData->name;
        $entity->first_name = $dbData->first_name;
        $entity->tenant_id = $dbData->tenant_id ?? '';

        return $entity;
    }

    public static function serializeCreate(string $name, string $firstName, string $tenantId): array
    {
        return [
            'name' => $name,
            'first_name' => $firstName,
            'tenant_id' => $tenantId,
        ];
    }

    public static function serializeUpdate(string $name, string $firstName): array
    {
        return [
            'name' => $name,
            'first_name' => $firstName,
        ];
    }
}
