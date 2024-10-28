<?php

namespace Vertuoza\Context;

use Psr\Http\Message\ServerRequestInterface;
use Vertuoza\Entities\CollaboratorEntity;

readonly class UserRequestContext
{
    public function __construct(
        private ?CollaboratorEntity $user = null,
        private ?ServerRequestInterface $request = null
    ) {}

    /**
     * @return string|null
     */
    public function getUserId(): string|null
    {
        return $this->user?->id ?: null;
    }

    /**
     * @return string|null
     */
    public function getTenantId(): string|null
    {
        return current($this->request->getHeader('api-tenant-id'));
    }

    /**
     * @return bool
     */
    public function isLogged(): bool
    {
        return ($this->getUserId() ?? null) && ($this->getTenantId() ?? null);
    }
}
