<?php

use Vertuoza\Entities\CollaboratorEntity;
use Vertuoza\Entities\TenantEntity;
use Vertuoza\Entities\UnitTypeEntity;
use Vertuoza\Repositories\CollaboratorRepository;
use Vertuoza\Repositories\TenantRepository;
use Vertuoza\Repositories\UnitTypeRepository;

return [
    UnitTypeEntity::class => UnitTypeRepository::class,
    TenantEntity::class => TenantRepository::class,
    CollaboratorEntity::class => CollaboratorRepository::class
];
