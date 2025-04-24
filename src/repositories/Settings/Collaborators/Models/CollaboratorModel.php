<?php

namespace Vertuoza\Repositories\Settings\Collaborators\Models;

use DateTime;
use stdClass;
use Vertuoza\Repositories\ModelInterface;

class CollaboratorModel implements ModelInterface
{
  public string $id;
  public string $name;
  public string $first_name;
  public ?DateTime $deleted_at;
  public string $tenant_id;
  
  /**
   * Create CollaboratorModel from standard class
   *
   * @param stdClass $data
   *
   * @return CollaboratorModel
   */
  public static function fromStdclass(stdClass $data): CollaboratorModel
  {
    $model = new CollaboratorModel();
    $model->id = $data->id;
    $model->name = $data->name;
    $model->first_name = $data->first_name;
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
    return 'collaborator';
  }
}
