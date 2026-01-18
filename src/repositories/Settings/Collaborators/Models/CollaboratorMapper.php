<?php

namespace Vertuoza\Repositories\Settings\Collaborators\Models;

use Vertuoza\Entities\Settings\CollaboratorEntity;

class CollaboratorMapper
{
    public static function modelToEntity(CollaboratorModel $model): CollaboratorEntity
    {
        return new CollaboratorEntity(
            $model->id,
            $model->name,
            $model->firstName
        );
    }
}