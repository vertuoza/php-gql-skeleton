<?php

namespace Vertuoza\Repositories\Settings\Collaborators\Models;

use Vertuoza\Repositories\Settings\Collaborators\Models\CollaboratorModel;
use Vertuoza\Entities\Settings\CollaboratorEntity;

class CollaboratorMapper
{
  /**
   * Create CollaboratorEntity from CollaboratorModel
   *
   * @param CollaboratorModel $dbData
   * 
   * @return CollaboratorEntity
   */
  public static function modelToEntity(CollaboratorModel $dbData): CollaboratorEntity
  {
    $entity = new CollaboratorEntity();
    $entity->id = $dbData->id . '';
    $entity->name = $dbData->name;
    $entity->firstName = $dbData->first_name;

    return $entity;
  }
}
