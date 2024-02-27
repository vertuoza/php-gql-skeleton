<?php

namespace Vertuoza\Repositories\Settings\CollaboratorTypes\Models;

use Vertuoza\Entities\Settings\CollaboratorEntity;
use Vertuoza\Repositories\Settings\CollaboratorTypes\CollaboratorTypeMutationData;

class CollaboratorMapper
{
    public static function modelToEntity(CollaboratorModel $dbData): CollaboratorEntity
    {
        $entity = new CollaboratorEntity();
        $entity->id = $dbData->id . '';
        $entity->name = $dbData->name;
        $entity->first_name = $dbData->first_name;
        $entity->isSystem = $dbData->tenant_id === null;

        return $entity;
    }

    public static function serializeUpdate(CollaboratorTypeMutationData $mutation): array
    {
        return self::serializeMutation($mutation);
    }

    public static function serializeCreate(CollaboratorTypeMutationData $mutation, string $tenantId): array
    {
        return self::serializeMutation($mutation, $tenantId);
    }

    private static function serializeMutation(CollaboratorTypeMutationData $mutation, string $tenantId = null): array
    {
        $data = [
            'name' => $mutation->name,
            'first_name' => $mutation->first_name,
        ];

        if ($tenantId) {
            $data[CollaboratorModel::getTenantColumnName()] = $tenantId;
        }
        return $data;
    }
}