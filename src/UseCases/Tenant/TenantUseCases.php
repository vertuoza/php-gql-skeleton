<?php

namespace Vertuoza\UseCases\Tenant;

use Vertuoza\Repositories\TenantRepository;

readonly class TenantUseCases
{
    /**
     * @var CreateUseCase
     */
    public CreateUseCase $create;
    public FindAllUseCase $findAll;
    public DeleteUseCase $delete;
    public UpdateUseCase $update;

    public function __construct(TenantRepository $tenantRepository)
    {
        $this->create = new CreateUseCase($tenantRepository);
        $this->findAll = new FindAllUseCase($tenantRepository);
        $this->delete = new DeleteUseCase($tenantRepository);
        $this->update = new UpdateUseCase($tenantRepository);
    }
}
