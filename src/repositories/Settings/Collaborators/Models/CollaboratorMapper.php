<?php
namespace Vertuoza\Repositories\Settings\Collaborators\Models;

use Vertuoza\Entities\Settings\CollaboratorEntity;

class CollaboratorMapper
{
  public static function modelToEntity(CollaboratorModel $model): CollaboratorEntity
  {
    $entity = new CollaboratorEntity();
    $entity->id = $model->id;
    $entity->name = $model->name;
    $entity->firstName = $model->firstName;
    return $entity;
  }
}