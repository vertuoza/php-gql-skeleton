<?php

namespace Vertuoza\Repositories\Settings\Collaborators\Models;

use stdClass;
use Vertuoza\Entities\Settings\CollaboratorEntity;
use Vertuoza\Repositories\Settings\Collaborators\Models\CollaboratorModel;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorMutationData;

class CollaboratorMapper
{
  public static function modelToEntity(CollaboratorModel $dbData): CollaboratorEntity
  {
    $entity = new CollaboratorEntity();
    $entity->id = $dbData->id . '';
    $entity->name = $dbData->name;
    $entity->firstName = $dbData->first_name;
    $entity->isSystem = $dbData->tenant_id === null;

    return $entity;
  }

}
