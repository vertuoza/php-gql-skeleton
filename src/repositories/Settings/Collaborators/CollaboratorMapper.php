<?php

namespace Vertuoza\Repositories\Settings\Collaborators;

use Vertuoza\Entities\CollaboratorEntity;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorModel;


use stdClass;


class CollaboratorMapper
{
    public static function modelToEntity(CollaboratorModel $dbData): CollaboratorEntity
    {
        $entity = new CollaboratorEntity();
        $entity->id = $dbData->id . '';
        $entity->name = $dbData->name;
        $entity->firstname = $dbData->firstname;

        return $entity;
    }

    public static function serializeUpdate(CollaboratorMutationData $mutation): array
    {
        return self::serializeMutation($mutation);
    }

    public static function serializeCreate(CollaboratorMutationData $mutation, string $tenantId): array
    {
        return self::serializeMutation($mutation, $tenantId);
    }

    private static function serializeMutation(CollaboratorMutationData $mutation, string $tenantId = null): array
    {
        $data = [
            'name' => $mutation->name,
            'firstname' => $mutation->firstname,
        ];

        if ($tenantId) {
            $data[CollaboratorModel::getTenantColumnName()] = $tenantId;
        }
        return $data;
    }
}
