<?php

namespace Vertuoza\Middleware;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Vertuoza\Context\UserRequestContext;
use Vertuoza\Kernel;

readonly class UserRequestContextMiddleware extends AbstractMiddleware
{
    /**
     * @param ServerRequestInterface $request
     * @param callable $next
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request, callable $next): mixed
    {
        return $next($request->withAttribute(
            'user-context',
            new UserRequestContext(
                userId: current($request->getHeader('api-user-id')),
                tenantId: current($request->getHeader('api-tenant-id'))
            )
        ));
    }
}