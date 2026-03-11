<?php

namespace Vertuoza\Repositories\Settings\Collaborators\Models;

use DateTime;
use stdClass;

/**
 * Database model for the collaborator table.
 * Represents the raw structure of a row in the collaborator table.
 */
class CollaboratorModel
{
  public string $id;
  public string $name;
  public string $first_name;
  public ?string $tenant_id;
  public ?DateTime $deleted_at;

  /**
   * Creates a CollaboratorModel from a raw database row.
   *
   * @param stdClass $data The raw database row
   * @return CollaboratorModel
   */
  public static function fromStdclass(stdClass $data): CollaboratorModel
  {
    $model = new CollaboratorModel();
    $model->id = $data->id;
    $model->name = $data->name;
    $model->first_name = $data->first_name;
    $model->tenant_id = $data->tenant_id;
    $model->deleted_at = $data->deleted_at;
    return $model;
  }

  /**
   * Returns the primary key column name.
   *
   * @return string
   */
  public static function getPkColumnName(): string { return 'id'; }

  /**
   * Returns the tenant foreign key column name.
   *
   * @return string
   */
  public static function getTenantColumnName(): string { return 'tenant_id'; }

  /**
   * Returns the table name.
   *
   * @return string
   */
  public static function getTableName(): string { return 'collaborator'; }
}