<?php

require_once __DIR__ . '/../vendor/autoload.php';

use GraphQL\Executor\Promise\Adapter\ReactPromiseAdapter as GraphQLReactPromiseAdapter;
use React\Http\Message\Response;
use React\Http\Message\ServerRequest;
use React\Http\Middleware\RequestBodyBufferMiddleware;
use React\Http\Middleware\RequestBodyParserMiddleware;
use React\Http\Middleware\StreamingRequestMiddleware;
use Vertuoza\Context\LogContext;
use Vertuoza\Enums\ExceptionErrorCodeEnum;
use Vertuoza\Kernel;
use Vertuoza\Logger\ApplicationLogger;
use Vertuoza\Logger\Logger;
use function React\Promise\resolve;

$kernel = Kernel::getInstance();
$kernel->init();
$kernel->load();

$logger = new Logger(
    ApplicationLogger::getInstance()
);

ini_set('memory_limit', $_ENV['MEMORY_LIMIT']);

try {
    $graphQLPromiseAdapter = new GraphQLReactPromiseAdapter();

    $http = new React\Http\HttpServer(
        new StreamingRequestMiddleware(),
        new RequestBodyBufferMiddleware(20 * 1024 * 1024),
        new RequestBodyParserMiddleware(20 * 1024 * 1024, 1),
        $kernel->getSandbox(),
        $kernel->getApiAccessMiddleware(),
        $kernel->getAppContextMiddleware(),
        $kernel->getUserRequestContextMiddleware(),
        $kernel->getLogContextMiddleware(),
        fn(...$args) => $kernel->getValidationUserContextMiddleware()->__invoke(...$args),
        $kernel->getGraphQLMiddleware(),
        fn (ServerRequest $request) => resolve(new Response(404))
    );

    $socket = new React\Socket\SocketServer('0.0.0.0:' . $_ENV['PORT']);
    $http->listen($socket);

    ApplicationLogger::getInstance()->info("Server running on port " . $_ENV['PORT'] . PHP_EOL);
} catch (Throwable $e) {
    ApplicationLogger::getInstance()->error(
        $e,
        ExceptionErrorCodeEnum::tryFrom($e->getCode()) ?? ExceptionErrorCodeEnum::UNDEFINED
    );
    exit(0);
}
