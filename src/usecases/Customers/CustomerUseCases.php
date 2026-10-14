<?php

namespace Vertuoza\Usecases\Customers;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

class CustomerUseCases
{
  public CustomerByIdUseCase $customerById;
  public CustomersFindManyUseCase $customersFindMany;

  public function __construct(UserRequestContext $userContext, RepositoriesFactory $repositories)
  {
    $this->customerById = new CustomerByIdUseCase($repositories, $userContext);
    $this->customersFindMany = new CustomersFindManyUseCase($repositories, $userContext);
  }
}