<?php

namespace Vertuoza\Repositories\Settings\UnitTypes\Models;

use stdClass;

class UnitTypeModel
{
  public string $id;
  public string $label;
  public ?string $tenant_id;
  public static function fromStdclass(stdClass $data): UnitTypeModel
  {
    $model = new UnitTypeModel();
    $model->id = $data->id;
    $model->label = $data->label;
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

  public static function getTableName(): string
  {
    return 'unit_type';
  }
}
