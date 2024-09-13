<?php

namespace Vertuoza\Repositories\Settings\Collaborators\Models;

use Vertuoza\Entities\Settings\CollaboratorEntity;

class CollaboratorMapper
{
    public static function modelToEntity(CollaboratorModel $dbData): CollaboratorEntity
    {
        $entity = new CollaboratorEntity();
        $entity->id = $dbData->id;
        $entity->name = $dbData->name;
        $entity->first_name = $dbData->first_name;

        return $entity;
    }
}