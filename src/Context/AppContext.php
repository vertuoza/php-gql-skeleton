<?php

namespace Vertuoza\Context;

use Psr\Http\Message\ServerRequestInterface;
use Vertuoza\Entities\CollaboratorEntity;
use Vertuoza\Factories\UseCasesFactory;

readonly class AppContext
{
    /**
     * @param ServerRequestInterface $request
     * @param UseCasesFactory $useCases
     * @param ?CollaboratorEntity $user
     */
    public function __construct(
        public ServerRequestInterface $request,
        public UseCasesFactory $useCases,
        public ?CollaboratorEntity $user
    ) {}

    /**
     * @return bool
     */
    public function isLogged(): bool
    {
        return (bool) $this->user;
    }

    public function isCollaboratorCompliance(): bool
    {
        if (!$this->getTenantId()) {
            return false;
        }

        return $this->user->isCollaboratorOfTenant($this->getTenantId());
    }

    public function isAdminCompliance(): bool
    {
        return $this->user->admin;
    }

    public function getTenantId(): ?string
    {
        return current($this->request->getHeader('api-tenant-id')) ?: '';
    }
}
