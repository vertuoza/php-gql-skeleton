<?php

namespace Vertuoza\Repositories;

use Illuminate\Database\Eloquent\Collection;
use React\Promise\PromiseInterface;
use Vertuoza\Entities\UnitTypeEntity;
use Vertuoza\Enums\FindTypeEum;
use function React\Async\async;

/**
 * @method Collection<UnitTypeEntity> findMany()
 * @method UnitTypeEntity find(string $id, FindTypeEum $findTypeEum = FindTypeEum::FIND)
 * @method UnitTypeEntity create(array $data)
 */
class UnitTypeRepository extends AbstractRepository
{
    /**
     * @return class-string<UnitTypeEntity>
     */
    public function getModel(): string
    {
        return UnitTypeEntity::class;
    }

    /**
     * @param string $name
     * @param string|int|null $excludeId
     *
     * @return PromiseInterface
     */
    public function countUnitTypeWithLabel(string $name, string|int|null $excludeId = null): PromiseInterface
    {
        die(__METHOD__);
        return async(
            fn() => $this->getQueryBuilder(QueryEnum::SELECT)
                ->where('label', $name)
                ->where(function ($query) use ($excludeId) {
                    if (isset($excludeId)) {
                        $query->where('id', '!=', $excludeId);
                    }
                })
        )();
    }
}
