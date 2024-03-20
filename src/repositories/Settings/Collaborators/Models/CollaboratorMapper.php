<?php

namespace Vertuoza\Repositories\Settings\Collaborators\Models;

use stdClass;
use Vertuoza\Entities\Settings\CollaboratorEntity;
use Vertuoza\Repositories\Settings\Collaborators\Models\CollaboratorModel;

class CollaboratorMapper
{
  private static array $existingIds = [];

  public static function modelToEntity(CollaboratorModel $dbData): CollaboratorEntity
  {
    $entity = new CollaboratorEntity();
    $entity->id = $dbData->id . '';
    $entity->name = $dbData->name;
    $entity->firstName = $dbData->first_name;

    if ($entity->id === '') {
      throw new \InvalidArgumentException('ID cannot be empty.');
    }

    if ($entity->name === '') {
      throw new \InvalidArgumentException('Name cannot be empty.');
    }

    if ($entity->firstName === '') {
      throw new \InvalidArgumentException('First name cannot be empty.');
    }

    self::$existingIds[$entity->id] = true;

    return $entity;
  }
}
