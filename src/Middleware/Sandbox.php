<?php

namespace Vertuoza\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use React\Http\Message\ServerRequest;
use Vertuoza\Kernel;
use function React\Promise\resolve;

class Sandbox
{
    public function __construct(
        protected Kernel $kernel
    ) {}

    /**
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    public function isSandboxRoute(ServerRequestInterface $request)
    {
        return !$this->kernel->getGraphQLMiddleware()->isGraphQLQueryPath($request)
            && $request->getMethod() === 'GET'
            && strtolower($_ENV['SANDBOX_ACTIVE']) === 'true';
    }

    /**
     * @param ServerRequest $request
     * @param callable $next
     *
     * @return mixed
     */
    public function __invoke (ServerRequest $request, callable $next): mixed
    {
        if (!$this->isSandboxRoute($request)) {
            return $next($request);
        }

        $sandboxhtml = file_get_contents('./assets/sandbox.html');
        $response = Response::plaintext($sandboxhtml);

        return resolve($response->withHeader('Content-Type', 'text/html'));
    }
}
