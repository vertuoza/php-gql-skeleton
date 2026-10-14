<?php

namespace Vertuoza\Repositories;

use Overblog\PromiseAdapter\PromiseAdapterInterface;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;
use Vertuoza\Repositories\Collaborators\CollaboratorRepository;
use Vertuoza\Repositories\Customers\CustomerRepository;

class RepositoriesFactory
{
  public UnitTypeRepository $unitType;
  public CollaboratorRepository $collaborator;
  public CustomerRepository $customer;


  public function __construct(QueryBuilder $database, PromiseAdapterInterface $dataLoaderPromiseAdapter)
  {
    $this->unitType = new UnitTypeRepository($database, $dataLoaderPromiseAdapter);
    $this->collaborator = new CollaboratorRepository($database, $dataLoaderPromiseAdapter);
    $this->customer = new CustomerRepository($database, $dataLoaderPromiseAdapter);
  }
}
