<?php

namespace Vertuoza\Repositories\Settings\Collaborator\Models;

use stdClass;
use Vertuoza\Repositories\Settings\Collaborator\Models\CollaboratorModel;
use Vertuoza\Entities\Settings\CollaboratorEntity;

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
}
