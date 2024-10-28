<?php

namespace Vertuoza\UseCases\Tenant;

use React\Promise\PromiseInterface;
use Vertuoza\Entities\CollaboratorEntity;
use Vertuoza\Entities\TenantEntity;
use Vertuoza\Enums\FindTypeEum;
use Vertuoza\Exceptions\ForbiddenException;
use Vertuoza\Exceptions\NotFoundException;
use Vertuoza\Repositories\TenantRepository;
use function React\Async\async;

class DeleteUseCase
{
    public function __construct(
        protected TenantRepository $tenantRepository
    )
    {}

    /**
     * @param string $id
     * @param CollaboratorEntity $owner
     * @return PromiseInterface
     * @throws ForbiddenException
     *
     * @throws NotFoundException
     */
    public function handle(
        string $id,
        CollaboratorEntity $owner
    ): PromiseInterface
    {
        $tenant = $this->tenantRepository->find($id, FindTypeEum::FIND_OR_FAILED);

        if ($tenant->owner != $owner)
        {
            throw new ForbiddenException('You are not the owner of this tenant.');
        }

        return async(
            function() use ($tenant) {
                $this->tenantRepository->delete($tenant->id);
                return $tenant;
            }
        )();
    }
}
