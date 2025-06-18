<?php

namespace Vertuoza\Repositories\Settings\UnitTypes\Models;

use DateTime;
use stdClass;

class UnitTypeModel
{
  public string $id;
  public string $label;
  public ?DateTime $deleted_at;
  public ?string $tenant_id;
  public static function fromStdclass(stdClass $data): UnitTypeModel
  {
    $model = new UnitTypeModel();
    $model->id = $data->id;
    $model->label = $data->label;
    $model->deleted_at = $data->deleted_at;
    $model->tenant_id = $data->tenant_id;
    return $model;
  }

  public static function getPkColumnName(): string
  {
    return 'id';
  }

  public static function getTenantColumnName(): string
  {
    return 'tenant_id';
  }

  public static function getLabelColumnName(): string
  {
    return 'label';
  }

  public static function getTableName(): string
  {
    return 'unit_type';
  }
}
