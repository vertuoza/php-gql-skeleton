<?php

namespace Vertuoza\Middleware;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use React\Promise\PromiseInterface;
use Vertuoza\Context\UserRequestContext;
use Vertuoza\Patterns\MiddlewarePattern;

readonly class UserRequestContextMiddleware extends MiddlewarePattern
{
    /**
     * @param ServerRequestInterface $request
     * @param ?callable $next
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request, ?callable $next): PromiseInterface
    {
        return $next($request->withAttribute(
            'user-context',
            new UserRequestContext(
                user: $this->getAppContext($request)->user ?? $this->getUser($request),
                request: $request
            )
        ));
    }
}