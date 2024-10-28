<?php

namespace Vertuoza\Repositories;

use Vertuoza\Entities\CollaboratorEntity;

class CollaboratorRepository extends AbstractRepository
{
    /**
     * @return class-string
     */
    public function getModel(): string
    {
        return CollaboratorEntity::class;
    }
}
