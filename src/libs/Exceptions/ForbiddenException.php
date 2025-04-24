<?php

namespace Vertuoza\Libs\Exceptions;

use Throwable;

class ForbiddenException extends BusinessException
{
  public const code = 403;

  public function __construct(string $message, string $errorCode = "FORBIDDEN", ?Throwable $previous = null, array|null $args = null)
  {
    parent::__construct($message, $errorCode, ForbiddenException::code, $previous, $args);
  }
}
