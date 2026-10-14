<?php

namespace Vertuoza\Api\Graphql\Resolvers\Customers;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;

class CustomersQuery
{
  static function get()
  {
    return [
      'customers' => [
        'type' => new NonNull(new ListOfType(new NonNull(Types::get(Customer::class)))),
        'resolve' => static fn ($rootValue, $args, RequestContext $context)
          => $context->useCases->customer->customersFindMany->handle(),
      ],
      'customerById' => [
        'type' => new NonNull(Types::get(Customer::class)),
        'args' => ['id' => new NonNull(Types::id())],
        'resolve' => static fn ($rootValue, $args, RequestContext $context)
          => $context->useCases->customer->customerById->handle($args['id']),
      ],
    ];
  }
}