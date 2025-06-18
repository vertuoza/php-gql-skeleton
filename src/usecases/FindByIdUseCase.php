<?php

namespace Vertuoza\Usecases;

use React\Promise\Promise;

class FindByIdUseCase extends UseCase
{
    public function handle(string|null $id = null): Promise
    {
        return $this->repository->getById($id, $this->context->getTenantId());
    }
}