<?php

namespace Vertuoza\Repositories\Customers\Models;

use Vertuoza\Repositories\Collaborators\Models\CollaboratorModel;

use stdClass;

class CustomerModel
{
  public string $id;
  public string $name;
  public string $first_name;
  public ?string $tenant_id;
  public string $collaborator_id; 
  public static function fromStdclass(stdClass $data): CustomerModel

  {
    $model = new CustomerModel();
    $model->id = $data->id;
    $model->name = $data->name;
    $model->first_name = $data->first_name;
    $model->tenant_id = $data->tenant_id;
    $model->collaborator_id =  $data->collaborator_id;
    return $model;
  }

  public static function getPkColumnName(): string
  {
    return 'id';
  }
   public static function getNameColumnName(): string
  {
    return 'name';
  }

  public static function getFirstNameColumnName(): string
  {
    return 'first_name';
  }

  public static function getTenantColumnName(): string
  {
    return 'tenant_id';
  }

  public static function getTableName(): string
  {
    return 'customer';
  }
   public static function getCollaborator(): string
  {
    return 'collaborator_id';
  }
}
