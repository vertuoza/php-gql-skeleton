<?php

namespace Vertuoza\Api\Graphql\Resolvers\Settings\UnitTypes;

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Api\Graphql\Types;
use Vertuoza\Libs\Exceptions\BadUserInputException;
use Vertuoza\Libs\Exceptions\FieldError;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Respect\Validation\Validator as Validation;
class UnitTypeMutation extends ObjectType
{
    public static function get(): array
    {

        return [
            'createUnitType' => [
                'type' => Types::get(UnitType::class),
                'args' => [
                    'name' => new NonNull(Type::string())
                ],
                'resolve' => function ($root, $args, RequestContext $context) {

                    $nameValidation = Validation::stringType()->length(3, 30);
                    if(!$nameValidation->validate($args['name'])){
                        throw new BadUserInputException(
                            new FieldError('name',
                                'name field cannot be empty or be longer than 30 characters',
                                'FIELD_ERROR',
                                '',
                                ["statusCode" => 403]
                            ),
                            'name')
                        ;
                    }else {
                        $mutation = new UnitTypeMutationData();
                        $mutation->name = $args['name'];
                        $newId = $context->useCases->unitType->unitTypeCreate->handle($mutation, $context);

                        return $context->useCases->unitType
                            ->unitTypeById
                            ->handle($newId, $context);
                    }
                }
            ]
        ];
    }
}