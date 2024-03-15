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
       
        if ($entity->id === '' || $entity->name === '' || $entity->firstName === '') {
            throw new \InvalidArgumentException('ID, name, and first name cannot be empty.');
        }

        self::$existingIds[$entity->id] = true;

        return $entity;
    }
}