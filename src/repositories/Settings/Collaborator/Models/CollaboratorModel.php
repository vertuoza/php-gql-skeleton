<?php

namespace Vertuoza\Repositories\Settings\Collaborator\Models;

use DateTime;
use stdClass;

class CollaboratorModel
{
  public string $id;
  public string $name;
  public string $first_name;
  public ?string $tenant_id;
  public ?DateTime $deleted_at;
  public static function fromStdclass(stdClass $data): CollaboratorModel
  {
    $model = new CollaboratorModel();
    $model->id = $data->id;
    $model->name = $data->name;
    $model->first_name = $data->first_name;
    $model->tenant_id = $data->tenant_id ?? null;
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