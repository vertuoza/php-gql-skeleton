<?php

namespace Vertuoza\Usecases\Customers;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\Customers\CustomerRepository;
use Vertuoza\Repositories\RepositoriesFactory;

class CustomersFindManyUseCase
{
  private CustomerRepository $customerRepository;
  private UserRequestContext $userContext;

  public function __construct(RepositoriesFactory $repositories, UserRequestContext $userContext)
  {
    $this->customerRepository = $repositories->customer;
    $this->userContext = $userContext;
  }

  public function handle()
  {
    return $this->customerRepository->findMany($this->userContext->getTenantId());
  }
}