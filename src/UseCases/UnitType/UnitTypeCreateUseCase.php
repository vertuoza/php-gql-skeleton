<?php

namespace Vertuoza\UseCases\UnitType;

use React\Promise\PromiseInterface;
use Vertuoza\Entities\TenantEntity;
use Vertuoza\Entities\UnitTypeEntity;
use Vertuoza\Repositories\UnitTypeRepository;
use function React\Async\async;

class UnitTypeCreateUseCase
{
    /**
     * @var UnitTypeRepository
     */
    private UnitTypeRepository $unitTypeRepository;


    /**
     * @param UnitTypeRepository $unitTypeRepository
     */
    public function __construct(
        UnitTypeRepository $unitTypeRepository
    ) {
        $this->unitTypeRepository = $unitTypeRepository;
    }

    /**
     * @param string $name
     * @param null|string $tenantId
     *
     * @return PromiseInterface<TenantEntity>
     */
    public function handle(
        string $name,
        ?string $tenantId
    ): PromiseInterface
    {
        return async(
            [$this->unitTypeRepository, 'create']
        )([
            'label' => $name,
            'tenant_id' => $tenantId
        ]);
    }
}
