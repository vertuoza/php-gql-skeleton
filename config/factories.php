<?php

use GraphQL\Executor\Promise\Adapter\ReactPromiseAdapter as GraphQLReactPromiseAdapter;
use GraphQL\Executor\Promise\PromiseAdapter as GraphQLPromiseAdapterInterface;
use GraphQL\Server\ServerConfig;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use Illuminate\Database\Capsule\Manager;
use Overblog\PromiseAdapter\Adapter\ReactPromiseAdapter as OverblogReactPromiseAdapter;
use Overblog\PromiseAdapter\PromiseAdapterInterface as OverblogPromiseAdapterInterface;
use React\Http\Message\ServerRequest;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Vertuoza\Context\AppContext;
use Vertuoza\Context\LogContext;
use Vertuoza\Factories\Factory;
use Vertuoza\Factories\ServerRequestFactory;
use Vertuoza\Handlers\GqlErrorHandler;
use Vertuoza\Interface\DatabaseInterface;
use Vertuoza\Middleware\ApiAccessMiddleware;
use Vertuoza\Middleware\AppContextMiddleware;
use Vertuoza\Middleware\GraphQLMiddleware;
use Vertuoza\Middleware\LogContextMiddleware;
use Vertuoza\Middleware\Sandbox;
use Vertuoza\Middleware\UserRequestContextMiddleware;
use Vertuoza\Middleware\ValidationUserContextMiddleware;
use Vertuoza\Repositories\CollaboratorRepository;
use Vertuoza\Repositories\TenantRepository;
use Vertuoza\Repositories\UnitTypeRepository;

return [
    // name =>  [factory,                       class, ...alias]
                [Factory::class,                AppContext::class],
                [Factory::class,                AppContextMiddleware::class],
                [Factory::class,                Sandbox::class],
                [Factory::class,                SchemaConfig::class],
                [Factory::class,                Schema::class],
                [Factory::class,                GraphQLMiddleware::class],
                [Factory::class,                GraphQLReactPromiseAdapter::class,
                                                GraphQLPromiseAdapterInterface::class],
                [Factory::class,                Serializer::class, SerializerInterface::class],
                [ServerRequestFactory::class,   ServerRequest::class],
                [Factory::class,                Manager::class, DatabaseInterface::class],
                [Factory::class,                ObjectType::class],
                [Factory::class,                UnitTypeRepository::class],
                [Factory::class,                CollaboratorRepository::class],
                [Factory::class,                TenantRepository::class],
                [Factory::class,                OverblogReactPromiseAdapter::class,
                                                OverblogPromiseAdapterInterface::class],
                [Factory::class,                ApiAccessMiddleware::class],
                [Factory::class,                ServerConfig::class],
                [Factory::class,                LogContext::class],
                [Factory::class,                ValidationUserContextMiddleware::class],
                [Factory::class,                UserRequestContextMiddleware::class],
                [Factory::class,                GqlErrorHandler::class],
                [Factory::class,                LogContextMiddleware::class],
];
