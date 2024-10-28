<?php

namespace Vertuoza\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Str;
use TypeError;
use Vertuoza\Enums\FindTypeEum;
use Vertuoza\Exceptions\ForbiddenException;
use Vertuoza\Exceptions\NotFoundException;

abstract class AbstractRepository
{
    /**
     * @return class-string<Model>
     */
    abstract public function getModel(): string;

    /**
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return $this->getQueryWithTrashed()
            ->whereNull('deleted_at');
    }

    /**
     * @return Builder
     */
    public function getQueryWithTrashed(): Builder
    {
        return $this->getModel()::query();
    }

    /**
     * @return Builder
     */
    public function getQueryOnlyTrashed(): Builder
    {
        return $this->getQueryWithTrashed()
            ->whereNotNull('deleted_at');
    }

    /**
     * @param string $id
     * @param FindTypeEum $findTypeEum
     *
     * @return Model
     *
     * @throws NotFoundException
     */
    public function find(string $id, FindTypeEum $findTypeEum = FindTypeEum::FIND): Model
    {
        try {
            return $this->getQuery()->{$findTypeEum->value}($id);
        } catch (TypeError|ModelNotFoundException $e) {
            throw new NotFoundException(
                previous: $e
            );
        }
    }

    /**
     * @param array $criteria
     * @param bool $orNull
     *
     * @return Collection
     */
    public function findBy(array $criteria, bool $orNull = false): Collection
    {
        $query = $this->getQuery();

        foreach ($criteria as $key => $value) {
            $query->where($key, $value);
            if ($orNull) {
                $query->orWhereNull($key);
            }
        }

        return $query->get();
    }

    public function findFirstBy(...$args): Model
    {
        return $this->findBy(...$args)->first();
    }

    /**
     * @return Collection<Model>
     */
    public function findMany(): Collection
    {
        return $this->getQuery()->get();
    }

    /**
     * @param array $data
     *
     * @return Model
     *
     * @throws ForbiddenException
     */
    public function create(array $data): Model
    {
        try {
            $data['id'] = Str::uuid();

            return $this->getQuery()->create($data);
        } catch (UniqueConstraintViolationException $constraintViolationException) {
            throw new ForbiddenException('Already exist');
        }
    }

    /**
     * @param string ...$ids
     *
     * @return Collection
     */
    public function findByIds(string ...$ids): Collection
    {
        return $this->getQuery()->findMany($ids);
    }

    /**
     * @param string|Model $id
     * @param array $data
     *
     * @return Model
     *
     * @throws ForbiddenException
     */
    public function update(string|Model $id, array $data): Model
    {
        try {
            $this->getQuery()->whereKey($id)->update($data);
        } catch (UniqueConstraintViolationException $e) {
            throw new ForbiddenException('Already exist');
        }

        return $this->getQuery()->find($id)->first();
    }

    /**
     * @param string|Model $id
     *
     * @return bool
     */
    public function delete(string|Model $id): bool
    {
        $this->update($id, ['deleted_at' => Carbon::now()]);

        return true;
    }
}