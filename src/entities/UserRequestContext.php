<?php

namespace Vertuoza\Entities;

readonly class UserRequestContext
{
    public function __construct(
        private string|null $userId = null,
        private string|null $tenantId = null
    ) {}

    /**
     * @return string|null
     */
    public function getUserId(): string|null
    {
        return $this->userId;
    }

    /**
     * @return string|null
     */
    public function getTenantId(): string|null
    {
        return $this->tenantId;
    }

    /**
     * @return bool
     */
    public function isLogged(): bool
    {
        return ($this->userId ?? null) && ($this->tenantId ?? null);
    }
}
