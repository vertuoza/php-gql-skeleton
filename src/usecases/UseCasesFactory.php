<?php

namespace Vertuoza\Usecases;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Usecases\Settings\UnitTypes\UnitTypeUseCases;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Usecases\Collaborators\CollaboratorUseCases;
use Vertuoza\Usecases\Customers\CustomerUseCases;

class UseCasesFactory
{
  public UnitTypeUseCases $unitType;
   public CollaboratorUseCases $collaborator;
   public CustomerUseCases $customer;

  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->unitType = new UnitTypeUseCases($userContext, $repositories);
    $this->collaborator = new CollaboratorUseCases($userContext, $repositories);
    $this->customer = new CustomerUseCases($userContext, $repositories);
  }
}
