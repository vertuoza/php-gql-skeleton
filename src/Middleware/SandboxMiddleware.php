<?php

namespace Vertuoza\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use React\Http\Message\ServerRequest;
use React\Promise\PromiseInterface;
use Vertuoza\Patterns\MiddlewarePattern;
use function React\Promise\resolve;
use Exception;

readonly class SandboxMiddleware extends MiddlewarePattern
{
    /**
     * @param ServerRequestInterface $request
     *
     * @return bool
     *
     * @throws Exception
     */
    public function isSandboxRoute(ServerRequestInterface $request): bool
    {
        return !$this->kernel->getGraphQLMiddleware()->isGraphQLQueryPath($request)
            && $request->getMethod() === 'GET'
            && strtolower($_ENV['SANDBOX_ACTIVE']) === 'true';
    }

    /**
     * @param ServerRequest $request
     *
     * @return bool
     *
     * @throws Exception
     */
    public function isSupported(ServerRequest $request): bool
    {
        return $this->isSandboxRoute($request);
    }

    /**
     * @param ServerRequest $request
     * @param ?callable $next
     *
     * @return mixed
     */
    public function handle(ServerRequest $request, ?callable $next): PromiseInterface
    {
        $sandboxhtml = $this->kernel->getSandboxContent('index');
        $response = Response::plaintext($sandboxhtml);

        return resolve($response->withHeader('Content-Type', 'text/html'));
    }
}
