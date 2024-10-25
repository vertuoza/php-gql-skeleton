<?php

namespace Vertuoza;

use Exception;
use GraphQL\Executor\Promise\PromiseAdapter as GraphQLPromiseAdapterInterface;
use GraphQL\Server\ServerConfig;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use Illuminate\Database\Capsule\Manager;
use Overblog\PromiseAdapter\PromiseAdapterInterface as OverblogPromiseAdapterInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\ServerRequest;
use Vertuoza\Entities\CollaboratorEntity;
use Vertuoza\Entities\TenantEntity;
use Vertuoza\Handlers\GqlErrorHandler;
use Vertuoza\Interface\DatabaseInterface;
use Vertuoza\Logger\ApplicationLogger;
use Vertuoza\Middleware\ApiAccessMiddleware;
use Vertuoza\Middleware\AppContextMiddleware;
use Vertuoza\Middleware\GraphQLMiddleware;
use Vertuoza\Middleware\LogContextMiddleware;
use Vertuoza\Middleware\Sandbox;
use Vertuoza\Middleware\UserRequestContextMiddleware;
use Vertuoza\Middleware\ValidationUserContextMiddleware;
use Vertuoza\Patterns\FactoryPattern;
use Vertuoza\Patterns\SingletonPattern;
use Vertuoza\Repositories\AbstractRepository;

class Kernel extends SingletonPattern
{
    /**
     * @var array
     */
    protected array $containers;

    /**
     * @var Manager
     */
    protected Manager $database;

    /**
     * @var ServerRequest
     */
    protected ServerRequest $serverRequest;

    /**
     * @var OverblogPromiseAdapterInterface
     */
    protected OverblogPromiseAdapterInterface $promiseAdapter;

    /**
     * @var ObjectType
     */
    protected ObjectType $query;
    protected ObjectType $mutation;

    /**
     * @var Schema
     */
    protected Schema $schema;

    /**
     * @var SchemaConfig
     */
    protected SchemaConfig $schemaConfig;
    protected array $repositories;
    protected array $useCases;
    protected Sandbox $sandbox;
    protected GraphQLMiddleware $graphqlMiddleware;
    protected GraphQLPromiseAdapterInterface $graphqlPromiseAdapter;
    protected array $graphqlTypes;
    protected ServerConfig $serverConfig;
    protected ApiAccessMiddleware $apiAccessMiddleware;
    protected ValidationUserContextMiddleware $validationUserContextMiddleware;
    protected UserRequestContextMiddleware $userRequestContextMiddleware;
    protected array $config;
    protected GqlErrorHandler $gqlErrorHandler;
    protected AppContextMiddleware $appContextMiddleware;
    protected ?ServerRequestInterface $request = null;
    protected LogContextMiddleware $logContextMiddleware;

    public function init(): void
    {
        $this->containers = [
            'factory' => []
        ];
    }

    /**
     * @return void
     */
    public function load(): void
    {
        $factoryMapper = $this->getConfigContent('factories');

        foreach ($factoryMapper as $name => $factorySetup)
        {
            $factory = array_shift($factorySetup);
            $className = array_shift($factorySetup);

            $index = $className;

            if (!is_numeric($name)) {
                $index = $name;
            }

            $this->containers['factory'][$index] = new $factory($className);

            foreach ($factorySetup as $alias) {
                $this->containers['factory'][$alias] = &$this->containers['factory'][$index];
            }
        }

        $this->getDatabase()->bootEloquent();
    }

    public function getServerRequest(): ServerRequest
    {
        return $this->serverRequest ??= $this->containers['factory'][ServerRequest::class]
            ->setHeaders()
            ->setServer()
            ->newInstance();
    }

    /**
     * @return Manager
     */
    public function getDatabase(): Manager
    {
        return $this->database ??= $this->containers['factory'][DatabaseInterface::class]
            ->addSetter('connection', $this->getConfigContent('databases'))
            ->newInstance();
    }

    public function getQuery(): ObjectType
    {
        return $this->query ??= $this->containers['factory'][ObjectType::class]
            ->addConstructorArgument('config', $this->getGraphqlSchema()['query'])
            ->newInstance();
    }

    public function getGraphqlSchema()
    {
        return $this->getConfigContent('graphql/schema');
    }

    /**
     * @return Schema
     */
    public function getSchema(): Schema
    {
        return $this->schema ??= $this->containers['factory'][Schema::class]
            ->addConstructorArgument('config', $this->getSchemaConfig())
            ->newInstance();
    }

    /**
     * @return SchemaConfig
     */
    public function getSchemaConfig(): SchemaConfig
    {
        return $this->schemaConfig ??= $this->containers['factory'][SchemaConfig::class]
            ->addSetter('query', $this->getQuery())
            ->addSetter('mutation', $this->getMutation())
            ->addSetter('typeLoader', [$this, 'getType'])
            ->newInstance();
    }

    public function getMutation(): ObjectType
    {
        return $this->mutation ??= $this->containers['factory'][ObjectType::class]
            ->addConstructorArgument('config', $this->getGraphqlSchema()['mutation'])
            ->newInstance();
    }

    /**
     * @return string
     */
    public function getConfigFolder(): string
    {
        return __DIR__.'/../config';
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function getConfigFilename(string $filename): string
    {
        return $this->getConfigFolder().'/'.$filename.'.php';
    }

    /**
     * @param string $filename
     *
     * @return mixed
     */
    public function getConfigContent(string $filename): mixed
    {
        return $this->config[$filename] ??= include $this->getConfigFilename($filename);
    }

    public function getOverblogPromiseAdapter(): OverblogPromiseAdapterInterface
    {
        return $this->promiseAdapter ??= $this->containers['factory'][OverblogPromiseAdapterInterface::class]
            ->newInstance();
    }

    public function getRepository(string $entityClassName): AbstractRepository
    {
        $repositoryName = $this->getConfigContent('repositories')[$entityClassName] ?? null;

        return $this->repositories[$repositoryName] ??= $this->containers['factory'][$repositoryName]
            ->newInstance();
    }

    public function getUseCase(string $useCaseClassName)
    {
        return $this->useCases[$useCaseClassName] ??= $this->containers['factory'][$useCaseClassName]
            ->newInstance();
    }

    public function getUserRequestContextMiddleware(): UserRequestContextMiddleware
    {
        return $this->userRequestContextMiddleware ??= $this->containers['factory'][UserRequestContextMiddleware::class]
            ->addConstructorArgument('kernel', $this)
            ->newInstance();
    }

    public function getAppContextMiddleware(): AppContextMiddleware
    {
        return $this->appContextMiddleware ??= $this->containers['factory'][AppContextMiddleware::class]
            ->addConstructorArgument('kernel', $this)
            ->newInstance();
    }

    public function getSandbox(): Sandbox
    {
        return $this->sandbox ??= $this->containers['factory'][Sandbox::class]
            ->addConstructorArgument('kernel', $this)
            ->newInstance();
    }

    /**
     * @return GraphQLMiddleware
     * @throws Exception
     */
    public function getGraphQLMiddleware(): GraphQLMiddleware
    {
        return $this->graphqlMiddleware ??= $this->getMiddlewareFactory(GraphQLMiddleware::class)
            ->newInstance();
    }

    public function getGraphQLPromiseAdapter(): GraphQLPromiseAdapterInterface
    {
        return $this->graphqlPromiseAdapter ??= $this->containers['factory'][GraphQLPromiseAdapterInterface::class]
            ->newInstance();
    }

    public function getType($name)
    {
        return $this->getTypes()[$name] ?? null;
    }

    public function getTypes()
    {
        return $this->graphqlTypes ??= $this->getGraphqlSchema()['types'];
    }

    public function getApiAccessMiddleware(): ApiAccessMiddleware
    {
        return $this->apiAccessMiddleware ??= $this->getMiddlewareFactory(ApiAccessMiddleware::class)
                ->addConstructorArgument('config', $this->getConfigContent('api_access'))
                ->newInstance();
    }

    /**
     * @return ServerConfig
     */
    public function getServerConfig(): ServerConfig
    {
        return $this->serverConfig ??= $this->containers['factory'][ServerConfig::class]
            ->addSetter(
                'ErrorsHandler',
                fn (array $errors, ?callable $formatter) =>
                    $this->getGqlErrorHandler()->handle($errors, $formatter)
            )
            ->newInstance();
    }

    /**
     * @return ApplicationLogger
     */
    public function getApplicationLogger(): ApplicationLogger
    {
        return ApplicationLogger::getInstance();
    }

    /**
     * @return GqlErrorHandler
     */
    public function getGqlErrorHandler(): GqlErrorHandler
    {
        return $this->gqlErrorHandler ??= $this->containers['factory'][GqlErrorHandler::class]
            ->addConstructorArgument('applicationLogger', $this->getApplicationLogger())
            ->newInstance();
    }

    public function getValidationUserContextMiddleware(): ValidationUserContextMiddleware
    {
        return $this->validationUserContextMiddleware ??= $this->getMiddlewareFactory(ValidationUserContextMiddleware::class)
            ->addConstructorArgument('tenantRepository', $this->getRepository(TenantEntity::class))
            ->addConstructorArgument('collaboratorRepository', $this->getRepository(CollaboratorEntity::class))
            ->newInstance();
    }

    public function getLogContextMiddleware(): LogContextMiddleware
    {
        return $this->logContextMiddleware ??= $this->getMiddlewareFactory(LogContextMiddleware::class)
            ->newInstance();
    }

    /**
     * @param $className
     * @return FactoryPattern
     *
     * @throws Exception
     */
    public function getFactory($className): FactoryPattern
    {
        return $this->containers['factory'][$className] ?? throw new Exception($className);
    }

    /**
     * @param string $middlewareClassName
     * @return FactoryPattern
     *
     * @throws Exception
     */
    public function getMiddlewareFactory(string $middlewareClassName): FactoryPattern
    {
        return $this->getFactory($middlewareClassName)
            ->addConstructorArgument('kernel', $this);
    }
}
