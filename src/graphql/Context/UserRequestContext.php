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

  public function isVertuoza(): bool
  {
    return $this->tenantId === "112c33ae-3dbe-431b-994d-fffffe6fd49b";
  }
}
