<?php

namespace Vertuoza\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Vertuoza\Enums\FindTypeEum;

abstract class AbstractRepository
{
    /**
     * @return class-string<Model>
     */
    abstract public function getModel(): string;

    /**
     * @param string $id
     * @param FindTypeEum $findTypeEum
     *
     * @return Model
     */
    public function find(string $id, FindTypeEum $findTypeEum = FindTypeEum::FIND): Model
    {
        return $this->getModel()::query()->{$findTypeEum->value}($id);
    }

    /**
     * @return Collection<Model>
     */
    public function findMany(): Collection
    {
        return $this->getModel()::query()->get();
    }

    /**
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data): Model
    {
        $data['id'] = Str::uuid();

        return $this->getModel()::query()->create($data);
    }

    /**
     * @param string ...$ids
     *
     * @return Collection
     */
    public function findByIds(string ...$ids): Collection
    {
        return $this->getModel()::query()->findMany($ids);
    }

    /**
     * @param string|Model $id
     * @param array $data
     *
     * @return Model
     */
    public function update(string|Model $id, array $data): Model
    {
        $this->getModel()::query()->whereKey($id)->update($data);

        if ($id instanceof Model) {
            var_dump($id);
            die();
            return $id;
        }

        return $this->find($id);
    }
}