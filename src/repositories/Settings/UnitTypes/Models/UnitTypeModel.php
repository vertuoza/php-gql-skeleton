<?php

namespace Vertuoza\Repositories\Settings\UnitTypes\Models;

use DateTime;
use stdClass;
use Vertuoza\Repositories\ModelInterface;

class UnitTypeModel implements ModelInterface
{
  public string $id;
  public string $label;
  public ?DateTime $deleted_at;
  public ?string $tenant_id;

  /**
   * Create UnitTypeModel from standard class
   *
   * @param stdClass $data
   *
   * @return UnitTypeModel
   */
  public static function fromStdclass(stdClass $data): UnitTypeModel
  {
    $model = new UnitTypeModel();
    $model->id = $data->id;
    $model->label = $data->label;
    $model->deleted_at = $data->deleted_at;
    $model->tenant_id = $data->tenant_id;
    return $model;
  }

  /**
   * Get the ID column name
   *
   * @return string
   */
  public static function getPkColumnName(): string
  {
    return 'id';
  }

  /**
   * Get the tenant ID column name
   *
   * @return string
   */
  public static function getTenantColumnName(): string
  {
    return 'tenant_id';
  }

  /**
   * Get the table name
   *
   * @return string
   */
  public static function getTableName(): string
  {
    return 'unit_type';
  }
}
