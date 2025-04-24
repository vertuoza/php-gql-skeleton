<?php

namespace Vertuoza\Repositories;

use stdClass;

interface ModelInterface
{
  /**
  * Create class into model
  *
  * @param stdClass $data
  *
  * @return mixed
  */
  public static function fromStdclass(stdClass $data): mixed;

  /**
   * Get the ID column name
   *
   * @return string
   */
  public static function getPkColumnName(): string;

  /**
   * Get the tenant ID column name
   *
   * @return string
   */
  public static function getTenantColumnName(): string;

  /**
   * Get the table name
   *
   * @return string
   */
  public static function getTableName(): string;
}
