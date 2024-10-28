<?php

namespace Vertuoza\UseCases\UnitType;

use React\Promise\PromiseInterface;
use Vertuoza\Patterns\UseCasePattern;
use Vertuoza\Repositories\UnitTypeRepository;
use function React\Async\async;

class UnitTypeFindManyUseCase extends UseCasePattern
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
     * @param string|null $tenantId
     *
     * @return PromiseInterface
     */
    public function handle(?string $tenantId): PromiseInterface
    {
        if (!$tenantId) {
            return async(
                [$this->unitTypeRepository, 'findMany']
            )();
        }

        return async(
            [$this->unitTypeRepository, 'findBy']
        )(['tenant_id' => $tenantId], true);
    }
}
