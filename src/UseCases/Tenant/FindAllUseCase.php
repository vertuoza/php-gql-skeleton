<?php

namespace Vertuoza\UseCases\Tenant;

use React\Promise\PromiseInterface;
use Vertuoza\Entities\TenantEntity;
use Vertuoza\Repositories\TenantRepository;
use function React\Async\async;

class FindAllUseCase
{
    public function __construct(
        protected TenantRepository $tenantRepository
    )
    {}

    /**
     *
     * @return PromiseInterface<TenantEntity>
     */
    public function handle(): PromiseInterface
    {
        return async(
            [$this->tenantRepository, 'findMany']
        )();
    }
}
