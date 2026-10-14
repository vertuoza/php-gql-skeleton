<?php

namespace Vertuoza\Usecases\Customers;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Collaborators\CollaboratorRepository;
use Vertuoza\Libs\Exceptions\NotFoundException;
use Vertuoza\Repositories\Customers\CustomerRepository;

class CustomerByIdUseCase
{
  private CustomerRepository $customerRepository;
  private UserRequestContext $userContext;

  public function __construct(RepositoriesFactory $repositories, UserRequestContext $userContext)
  {
    $this->customerRepository = $repositories->customer;
    $this->userContext = $userContext;
  }

  public function handle(string $id): Promise
  {
    return $this->customerRepository->getById($id, $this->userContext->getTenantId())
      ->then(function ($entity) use ($id) {
        if ($entity === null) {
          throw new NotFoundException(
            "Customer with id \"{$id}\" not found.",
            "CUSTOMER_NOT_FOUND",
            null,
            ['id' => $id]
          );
        }
        return $entity;
      });
  }
}