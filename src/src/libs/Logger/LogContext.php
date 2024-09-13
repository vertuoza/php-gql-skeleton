<?php

namespace Vertuoza\Libs\Logger;

class LogContext
{
  private string|null $tenantId;
  private string|null $userId;

  function __construct(string|null $tenantId, string|null $userId)
  {
    $this->tenantId = $tenantId;
    $this->userId = $userId;
  }

  public function getTenantId(): string|null
  {
    return $this->tenantId;
  }

  public function getUserId(): string|null
  {
    return $this->userId;
  }
}
