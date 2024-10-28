<?php

namespace Vertuoza\Factories;

use Vertuoza\Entities\CollaboratorEntity;
use Vertuoza\Entities\TenantEntity;
use Vertuoza\Entities\UnitTypeEntity;
use Vertuoza\Kernel;
use Vertuoza\Repositories\CollaboratorRepository;
use Vertuoza\Repositories\TenantRepository;
use Vertuoza\Repositories\UnitTypeRepository;
use Vertuoza\UseCases\Tenant\TenantUseCases;
use Vertuoza\UseCases\UnitType\UnitTypeUseCases;
use Vertuoza\UseCases\User\UserUseCases;

/**
 * @deprecated move to kernel
 */
readonly class UseCasesFactory
{
    /**
     * @var UnitTypeUseCases $unitType
     */
    public UnitTypeUseCases $unitType;
    public UserUseCases $user;
    public TenantUseCases $tenant;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->unitType = new UnitTypeUseCases($this->getRepositoryUnitTypeRepository());
        $this->user = new UserUseCases($this->getCollaboratorRepository());
        $this->tenant = new TenantUseCases($this->getTenantRepository());
    }

    /**
     * @return UnitTypeRepository
     *
     * @throws \Exception
     */
    public function getRepositoryUnitTypeRepository(): UnitTypeRepository
    {
        $kernel = Kernel::getInstance();
        $repository = $kernel->getRepository(UnitTypeEntity::class);

        if (!$repository instanceof UnitTypeRepository) {
            throw new \Exception();
        }

        return $repository;
    }

    public function getCollaboratorRepository(): CollaboratorRepository
    {
        $kernel = Kernel::getInstance();
        $repository = $kernel->getRepository(CollaboratorEntity::class);

        if (!$repository instanceof CollaboratorRepository) {
            throw new \Exception();
        }

        return $repository;
    }

    public function getTenantRepository(): TenantRepository
    {
        $kernel = Kernel::getInstance();
        $repository = $kernel->getRepository(TenantEntity::class);

        if (!$repository instanceof TenantRepository) {
            throw new \Exception();
        }

        return $repository;
    }
}
