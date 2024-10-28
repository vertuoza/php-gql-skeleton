<?php

namespace Vertuoza\UseCases\Tenant;

use React\Promise\PromiseInterface;
use Vertuoza\Entities\CollaboratorEntity;
use Vertuoza\Repositories\TenantRepository;
use function React\Async\async;

class CreateUseCase
{
    public function __construct(
        protected TenantRepository $tenantRepository
    )
    {}

    /**
     * @param CollaboratorEntity $owner
     * @param array $collaborators
     * @param string $name
     *
     * @return PromiseInterface
     */
    public function handle(
        CollaboratorEntity $owner,
        array $collaborators,
        string $name,
    ): PromiseInterface
    {
        return async(
            function($data) use ($collaborators){
                $tenant = $this->tenantRepository->create($data);
                foreach ($collaborators as $collaborator) {
                    $tenant->collaborators()->attach($collaborator);
                }
                return $this->tenantRepository->find($tenant->id);
            }
        )([
            'name' => $name,
            'owner_id' => $owner->id
        ]);
    }
}
