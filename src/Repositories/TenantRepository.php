<?php

namespace Vertuoza\Repositories;

use Vertuoza\Entities\TenantEntity;

class TenantRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public function getModel(): string
    {
        return TenantEntity::class;
    }
}
