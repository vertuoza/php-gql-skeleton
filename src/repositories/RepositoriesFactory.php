<?php

namespace Vertuoza\Repositories;

use Overblog\PromiseAdapter\PromiseAdapterInterface;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;
use Vertuoza\Repositories\Settings\CollaboratorTypes\CollaboratorTypeRepository;

class RepositoriesFactory
{
  public UnitTypeRepository $unitType;
  public CollaboratorTypeRepository $collaborators;

  public function __construct(QueryBuilder $database, PromiseAdapterInterface $dataLoaderPromiseAdapter)
  {
    $this->unitType = new UnitTypeRepository($database, $dataLoaderPromiseAdapter);
    $this->collaborators = new CollaboratorTypeRepository($database, $dataLoaderPromiseAdapter);
  }
}
