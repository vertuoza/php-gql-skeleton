<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\Hello;

use Vertuoza\Api\Graphql\Types;

class HelloQuery
{
  static function get()
  {
    return [
      'hello' => [
        'description' => 'Just a "Hello world" for first test',
        'type' => Types::string(),
        'resolve' => function ($root, $args) {
          return 'world';
        }
      ],
    ];
  }
}
