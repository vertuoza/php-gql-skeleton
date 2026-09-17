<?php

namespace Vertuoza\Repositories\Organization\Collaborators\Models;

use Vertuoza\Entities\Organization\CollaboratorEntity;

class CollaboratorMapper
{
    public static function modelToEntity(CollaboratorModel $dbData): CollaboratorEntity
    {
        return new CollaboratorEntity(
            id: $dbData->id,
            firstName: $dbData->first_name,
            name: $dbData->name,
        );
    }
}