<?php

namespace Vertuoza\UseCases\UnitType;

use Vertuoza\Repositories\UnitTypeRepository;

readonly class UnitTypeUseCases
{
    /**
     * @var UnitTypeByIdUseCase
     */
    public UnitTypeByIdUseCase $unitTypeById;

    /**
     * @var UnitTypeFindManyUseCase
     */
    public UnitTypeFindManyUseCase $unitTypeFindMany;

    /**
     * @var UnitTypeCreateUseCase
     */
    public UnitTypeCreateUseCase $unitTypeCreate;

    /**
     * @param UnitTypeRepository $unitTypeRepository
     */
    public function __construct(UnitTypeRepository $unitTypeRepository)
    {
        $this->unitTypeById = new UnitTypeByIdUseCase($unitTypeRepository);
        $this->unitTypeFindMany = new UnitTypeFindManyUseCase($unitTypeRepository);
        $this->unitTypeCreate = new UnitTypeCreateUseCase($unitTypeRepository);
    }
}
