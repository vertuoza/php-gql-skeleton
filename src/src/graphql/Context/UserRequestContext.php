<?php

namespace Vertuoza\Api\Graphql\Context;

class UserRequestContext
{
  private string|null $userId;
  private string|null $tenantId;

  public function __construct(string|null $userId, string|null $tenantId)
  {
    $this->userId = $userId;
    $this->tenantId = $tenantId;
  }

  public function getUserId(): string|null
  {
    return $this->userId;
  }

  public function getTenantId(): string|null
  {
    return $this->tenantId;
  }

  public function isLogged(): bool
  {
    return $this->userId !== null && $this->tenantId !== null;
  }
}
