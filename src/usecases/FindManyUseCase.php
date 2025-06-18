<?php

namespace Vertuoza\Usecases;

use React\Promise\Promise;

class FindManyUseCase extends UseCase
{
    public function handle(string|null $id = null): Promise
    {
        return $this->repository->findMany($this->context->getTenantId());
    }
}