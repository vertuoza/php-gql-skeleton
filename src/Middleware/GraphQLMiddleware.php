<?php

namespace Vertuoza\Middleware;

use Exception;
use Fig\Http\Message\RequestMethodInterface;
use GraphQL\Error\Error;
use GraphQL\Executor\ExecutionResult;
use GraphQL\Executor\Promise\PromiseAdapter;
use GraphQL\GraphQL;
use GraphQL\Server\ServerConfig;
use GraphQL\Type\Schema as SchemaType;
use JsonException;
use Overblog\DataLoader\DataLoader;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use React\Http\Message\ServerRequest;
use React\Promise\PromiseInterface;
use Vertuoza\Exceptions\NotFoundException;
use Vertuoza\Patterns\MiddlewarePattern;
use function React\Async\async;

readonly class GraphQLMiddleware extends MiddlewarePattern
{
    public function __construct(
        protected ServerConfig $serverConfig,
        protected PromiseAdapter $promiseAdapter,
        protected SchemaType $schema,
        ...$args
    )
    {
        parent::__construct(...$args);
    }

    /**
     * @param ServerRequestInterface|null $request
     *
     * @return bool
     */
    public function isGraphQLQueryPath(ServerRequestInterface $request = null): bool
    {
        if (!$request) {
            return false;
        }

        $method = $request->getMethod();

        if ($method !== RequestMethodInterface::METHOD_POST) {
            return false;
        }

        if ($request->getUri()->getPath() === "/graphql") {
            return true;
        }

        if (str_starts_with($request->getUri()->getPath(), "/graphql/")) {
            return true;
        }

        return false;
    }

    public function isSupported(ServerRequest $request): bool
    {
        return $this->isGraphQLQueryPath($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ?callable $next
     *
     * @return PromiseInterface
     *
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request, ?callable $next): PromiseInterface
    {
        $rawInput = $request->getBody()->__toString();
        $input = json_decode($rawInput, true);
        $query = $input['query'];
        $variableValues = $input['variables'] ?? null;
        $rootValue = ['prefix' => ''];

        $promise = async(
            fn() => GraphQL::promiseToExecute(
                $this->promiseAdapter,
                $this->schema,
                $query,
                $rootValue,
                $this->getAppContext($request),
                $variableValues
        ))();

/*
            $promise->catch(function ($error){

                var_dump(func_get_args());
                die('lll');
            });
        } catch (Exception $exception) {
            $kernel = Kernel::getInstance();
            $config = $kernel->getServerConfig();
            return resolve($handler(
                new ExecutionResult(
                    errors: $config->getErrorsHandler()(
                        [
                            new Error(
                                message: $exception->getMessage(),
                                previous: new BadRequestException(
                                    message: $exception->getMessage(),
                                    previous: $exception
                                )
                            )
                        ],
                        $config->getErrorFormatter()
                    )
                )
            ));
        }*/

        //$promise->then([$this, 'parseErrorsHandler']);

        DataLoader::await($promise);
        return $promise->then([$this, 'getResponse']);
    }

    /**
     * @throws JsonException
     */
    public function getResponse(ExecutionResult $result): Response
    {
        if ($result->errors) {
            throw current($result->errors);
        }

        $resultArr = $result->toArray();
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($resultArr, JSON_THROW_ON_ERROR)
        );
    }

    /**
     * @param ExecutionResult $result
     *
     * @return void
     */
    public function parseErrorsHandler(ExecutionResult $result): void
    {
        if (empty($result->errors)) {
            return;
        }

        $this->kernel->getApplicationLogger()->error(
            'An error has occurred',
            metadata: $result->errors
        );
        $result->errors = $this->serverConfig->getErrorsHandler()($result->errors, $this->serverConfig->getErrorFormatter());
    }

    public function getNotFoundErrorExecutionResult(): ExecutionResult
    {
        return new ExecutionResult(
            errors: $this->serverConfig->getErrorsHandler()(
                [
                    $this->getNotFoundError()
                ],
                $this->serverConfig->getErrorFormatter()
            )
        );
    }

    /**
     * @return Error
     */
    public function getNotFoundError(): Error
    {
        return new Error(
            previous: new NotFoundException()
        );
    }
}
