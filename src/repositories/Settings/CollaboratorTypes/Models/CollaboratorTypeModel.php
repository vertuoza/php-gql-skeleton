<?php

namespace Vertuoza\Repositories\Settings\CollaboratorTypes\Models;

use DateTime;
use stdClass;

class CollaboratorTypeModel
{
  public string $id;
  public ?string $tenant_id;
  public string $name;
  public string $first_name;
  public ?DateTime $deleted_at;

  public static function fromStdclass(stdClass $data): CollaboratorTypeModel
  {
    $model = new CollaboratorTypeModel();
    $model->id = $data->id;
    $model->tenant_id = $data->tenant_id;
    $model->name = $data->name;
    $model->first_name = $data->first_name;
    $model->deleted_at = $data->deleted_at;
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
    return 'collaborator';
  }
}
