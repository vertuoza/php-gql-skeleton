<?php

namespace Vertuoza\Repositories\Organization\Collaborators\Models;

use DateTime;
use stdClass;

class CollaboratorModel
{
  public string $id;
  public string $first_name;
  public string $name;
  public ?DateTime $deleted_at;
  public string $tenant_id;

  public static function fromStdclass(stdClass $data): CollaboratorModel
  {
    $model = new CollaboratorModel();
    $model->id = (string) $data->id;
    $model->first_name = $data->first_name;
    $model->name = $data->name;
    $model->deleted_at = $data->deleted_at;
    $model->tenant_id = (string) $data->tenant_id;

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
