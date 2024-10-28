<?php

namespace Vertuoza\UseCases\Tenant;

use React\Promise\PromiseInterface;
use Vertuoza\Entities\CollaboratorEntity;
use Vertuoza\Enums\FindTypeEum;
use Vertuoza\Exceptions\ForbiddenException;
use Vertuoza\Exceptions\NotFoundException;
use Vertuoza\Repositories\TenantRepository;
use function React\Async\async;

class UpdateUseCase
{
    public function __construct(
        protected TenantRepository $tenantRepository
    )
    {}

    /**
     * @param string $id
     * @param array|null $collaborators
     * @param string|null $name
     * @param CollaboratorEntity $owner
     *
     * @return PromiseInterface
     */
    public function handle(
        string $id,
        CollaboratorEntity $owner,
        ?array $collaborators = null,
        ?string $name = null
    ): PromiseInterface
    {
        return async(
            function() use ($id, $name, $collaborators, $owner){
                $tenant = $this->tenantRepository->find($id, FindTypeEum::FIND_OR_FAILED);

                if ($tenant->owner != $owner) {
                    throw new ForbiddenException('You are not owner of tenant');
                }

                if ($name !== null) {
                    $tenant = $this->tenantRepository->update($tenant, ['name' => $name]);
                }

                if ($collaborators !== null) {
                    $tenant->collaborators()->detach();
                    foreach ($collaborators as $collaborator) {
                        $tenant->collaborators()->attach($collaborator);
                    }
                }

                return $this->tenantRepository->find($tenant->id);
            }
        )();
    }
}
