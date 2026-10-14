<?php

namespace Vertuoza\Repositories\Customers\Models;

use Vertuoza\Entities\Collaborators\CollaboratorEntity;
use Vertuoza\Entities\Customers\CustomerEntity;
use Vertuoza\Repositories\Customers\Models\CustomerModel;

class CustomerMapper
{
  public static function modelToEntity(CustomerModel $dbData): CustomerEntity
  {
    $entity = new CustomerEntity();
    $entity->id = $dbData->id . '';
    $entity->name = $dbData->name;
    $entity->firstName = $dbData->first_name;
    $entity->collaboratorId = $dbData->collaborator_id;

    return $entity;
  }
}
