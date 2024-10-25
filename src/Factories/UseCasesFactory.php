<?php

namespace Vertuoza\Factories;

use Vertuoza\Entities\UnitTypeEntity;
use Vertuoza\Kernel;
use Vertuoza\Usecases\UnitType\UnitTypeUseCases;

/**
 * @deprecated move to kernel
 */
readonly class UseCasesFactory
{
    /**
     * @var UnitTypeUseCases $unitType
     */
    public UnitTypeUseCases $unitType;

    /**
     * Constructor
     */
    public function __construct()
    {
        $kernel = Kernel::getInstance();

        $this->unitType = new UnitTypeUseCases($kernel->getRepository(UnitTypeEntity::class));
    }
}
