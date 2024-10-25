<?php

namespace Vertuoza\Middleware;

use Closure;
use Exception;
use Fig\Http\Message\RequestMethodInterface;
use GraphQL\Executor\ExecutionResult;
use GraphQL\GraphQL;
use Overblog\DataLoader\DataLoader;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use Vertuoza\Kernel;

readonly class GraphQLMiddleware extends AbstractMiddleware
{
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

    /**
     * @param ServerRequestInterface $request
     * @param callable $next
     *
     * @return Closure
     *
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request, callable $next): mixed
    {
        if (!$this->isGraphQLQueryPath($request)) {
            return $next($request);
        }

        $kernel = Kernel::getInstance();

        $rawInput = $request->getBody()->__toString();
        $input = json_decode($rawInput, true);
        $query = $input['query'];
        $variableValues = $input['variables'] ?? null;
        $rootValue = ['prefix' => ''];

        $handler = function (ExecutionResult $result): Response {
            $resultArr = $result->toArray();
            return new Response(200, ['Content-Type' => 'application/json'], json_encode($resultArr, JSON_THROW_ON_ERROR));
        };

        $promise = GraphQL::promiseToExecute(
            $kernel->getGraphQLPromiseAdapter(),
            $kernel->getSchema(),
            $query,
            $rootValue,
            $this->getAppContext($request),
            $variableValues
        );

        $promise->then(function (ExecutionResult $result){
            if (!empty($result->errors)) {
                $kernel = Kernel::getInstance();
                $config = $kernel->getServerConfig();

                $kernel->getApplicationLogger()->info(
                        'Log for body with error',
                        $result->errors
                    );
                $result->errors = $config->getErrorsHandler()($result->errors, $config->getErrorFormatter());
            }
        });

        DataLoader::await($promise);
        return $promise->then($handler);
    }
}
