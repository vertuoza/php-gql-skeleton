<?php

namespace Vertuoza\Repositories\Settings\Collaborators\Models;

use Vertuoza\Entities\Settings\CollaboratorEntity;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorMutationData;

class CollaboratorMapper
{
  public static function modelToEntity(CollaboratorModel $dbData): CollaboratorEntity
  {
    $entity = new CollaboratorEntity();
    $entity->id = $dbData->id . '';
    $entity->name = $dbData->name;
    $entity->firstName = $dbData->first_name;

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
        'first_name' => $mutation->firstName,
    ];

    if ($tenantId) {
      $data[CollaboratorModel::getTenantColumnName()] = $tenantId;
    }
    return $data;
  }
}
