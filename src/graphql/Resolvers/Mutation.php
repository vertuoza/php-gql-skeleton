<?php 
namespace Vertuoza\Api\Graphql\Resolvers;

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes\UnitTypeMutation;
use Vertuoza\Api\Graphql\Types;

class Mutation extends ObjectType {
  public function __construct()
  {
    $config = [
      'fields' => function () {
        return [
          ...UnitTypeMutation::get(),
        ];
      }
    ];
    parent::__construct($config);
  }
}