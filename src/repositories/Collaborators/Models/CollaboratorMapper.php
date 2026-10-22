<?php

namespace Vertuoza\Repositories\Collaborators\Models;

use Vertuoza\Entities\Collaborators\CollaboratorEntity;

class CollaboratorMapper
{
  public static function modelToEntity(CollaboratorModel $dbData): CollaboratorEntity
  {
    $entity = new CollaboratorEntity();
    $entity->id = $dbData->id;
    $entity->name = $dbData->name;
    $entity->firstName = $dbData->first_name;

    return $entity;
  }
}
