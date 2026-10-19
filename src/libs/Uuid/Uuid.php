<?php

namespace Vertuoza\Libs\Uuid;

final class Uuid
{
  /**
   * Generates a random (version 4) UUID.
   * Needed because the primary keys are CHAR(36) filled by a MySQL default,
   * which does not allow to know the id of an inserted row.
   */
  public static function v4(): string
  {
    $bytes = random_bytes(16);
    $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
    $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
  }
}
