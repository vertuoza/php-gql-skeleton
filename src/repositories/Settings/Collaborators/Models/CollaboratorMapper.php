<?php

namespace Vertuoza\Repositories\Settings\Collaborators\Models;

use Vertuoza\Entities\Settings\CollaboratorEntity;

/**
 * Mapper for Collaborator.
 * Converts database models to domain entities.
 */
class CollaboratorMapper
{
  /**
   * Converts a CollaboratorModel (database representation) to a CollaboratorEntity (domain representation).
   *
   * @param CollaboratorModel $model The database model to convert
   * @return CollaboratorEntity The resulting domain entity
   */
  public static function modelToEntity(CollaboratorModel $model): CollaboratorEntity
  {
    $entity = new CollaboratorEntity();
    $entity->id = $model->id . '';              // cast to string to ensure type safety
    $entity->name = $model->name;
    $entity->firstName = $model->first_name;    // snake_case → camelCase
    return $entity;
  }
}