<?php

namespace Vertuoza\Middleware;

use Closure;
use Exception;
use Fig\Http\Message\RequestMethodInterface;
use GraphQL\Executor\ExecutionResult;
use GraphQL\GraphQL;
use GraphQL\Server\ServerConfig;
use Overblog\DataLoader\DataLoader;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use Vertuoza\Errors\GqlErrorHandler;
use Vertuoza\Kernel;
use Vertuoza\Logger\ApplicationLogger;
use Vertuoza\Logger\LogContext;

class GraphQLMiddleware
{
    /**
     * @param ServerRequestInterface|null $request
     *
     * @return bool
     */
    public function isGraphQLQueryPath(ServerRequestInterface $request = null): bool
    {
        if (!$request) {
            $request = Kernel::getInstance()->getRequest();
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
        if (!$this->isGraphQLQueryPath()) {
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
            $kernel->getAppContext(),
            $variableValues
        );

        $promise->then(function (ExecutionResult $result){
            $kernel = Kernel::getInstance();
            $config = $kernel->getServerConfig();
            $userContext = $kernel->getUserContext();
            if (!empty($result->errors)) {
                ApplicationLogger::getInstance()->info('Log for body with error', new LogContext($userContext->getTenantId(), $userContext->getUserId()), $result->errors);
                $result->errors = $config->getErrorsHandler()($result->errors, $config->getErrorFormatter());
            }
        });

        DataLoader::await($promise);
        return $promise->then($handler);
    }
}
