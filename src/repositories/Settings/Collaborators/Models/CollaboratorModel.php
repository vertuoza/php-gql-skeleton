<?php
namespace Vertuoza\Repositories\Settings\Collaborators\Models;

class CollaboratorModel
{
  public string $id;
  public string $name;
  public string $firstName;

  public static function getTableName(): string
  {
    return 'collaborator';
  }

  public static function getPkColumnName(): string
  {
    return 'id';
  }

  public static function getTenantColumnName(): string
  {
    return 'tenant_id';
  }

  public static function fromStdclass(\stdClass $row): self
  {
    $model = new self();
    $model->id = $row->id;
    $model->name = $row->name;
    $model->firstName = $row->first_name;
    return $model;
  }
}