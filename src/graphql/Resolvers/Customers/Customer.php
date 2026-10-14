<?php

namespace Vertuoza\Api\Graphql\Resolvers\Customers;

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Resolvers\Collaborators\Collaborator;
use Vertuoza\Api\Graphql\Types;

class Customer extends ObjectType
{
  public function __construct()
  {
    parent::__construct([
      'name' => 'Customer',
      'description' => 'Customer',
      'fields' => static fn (): array => [
        'id' => [
          'description' => "Unique identifier of the customer",
          'type' =>  new NonNull(Types::id()),
        ],
        'name' => [
          'description' => "Name of the customer",
          'type' =>  new NonNull(Types::string()),
        ],
         'firstName' => [
          'description' => "First name of the customer",
          'type' =>  new NonNull(Types::string()),
        ],
          'collaborator' => [
          'description' => "Collaborator of the customer",
          'type' =>  new NonNull(Types::get(Collaborator::class)),
          'resolve' => static fn ($customer, $args, RequestContext $context)
              => $context->useCases->collaborator->collaboratorById->handle($customer->collaboratorId),
        ],
      ],
    ]);
  }
}
