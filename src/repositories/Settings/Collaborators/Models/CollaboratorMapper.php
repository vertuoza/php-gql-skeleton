<?php

namespace Vertuoza\Repositories\Settings\Collaborators\Models;

use Vertuoza\Entities\Settings\CollaboratorEntity;

class CollaboratorMapper
{
    /**
     * Convert a CollaboratorModel (database representation)
     * into a CollaboratorEntity (business representation).
     *
     * @param CollaboratorModel $dbData The database model to convert
     * @return CollaboratorEntity The mapped entity
     */
    public static function modelToEntity(CollaboratorModel $dbData): CollaboratorEntity
    {
        $entity = new CollaboratorEntity();
        $entity->id = $dbData->id;
        $entity->name = $dbData->name;
        $entity->firstName = $dbData->first_name;
        return $entity;
    }
}
