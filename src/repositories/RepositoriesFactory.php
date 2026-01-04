<?php

namespace Vertuoza\Repositories;

use Overblog\PromiseAdapter\PromiseAdapterInterface;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

class RepositoriesFactory
{
  public UnitTypeRepository $unitType;
  public CollaboratorRepository $collaborator_repository;

  public function __construct(QueryBuilder $database, PromiseAdapterInterface $dataLoaderPromiseAdapter)
  {
    $this->unitType = new UnitTypeRepository($database, $dataLoaderPromiseAdapter);
    $this->collaborator_repository = new CollaboratorRepository($database, $dataLoaderPromiseAdapter);
  }
}
