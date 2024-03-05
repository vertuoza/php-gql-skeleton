<?php

namespace Vertuoza\Repositories;

use Overblog\PromiseAdapter\PromiseAdapterInterface;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

class RepositoriesFactory
{
  public CollaboratorRepository $collaborator;
  public UnitTypeRepository $unitType;

  public function __construct(QueryBuilder $database, PromiseAdapterInterface $dataLoaderPromiseAdapter)
  {
    $this->collaborator = new CollaboratorRepository($database, $dataLoaderPromiseAdapter);
    $this->unitType = new UnitTypeRepository($database, $dataLoaderPromiseAdapter);
  }
}
