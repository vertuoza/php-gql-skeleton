<?php

namespace Vertuoza\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Vertuoza\Entities\UserRequestContext;
use Vertuoza\Kernel;

readonly  class UserRequestContextMiddleware
{
    public function __construct(
        protected Kernel $kernel
    ) {}

    public function __invoke(ServerRequestInterface $request, callable $next): mixed
    {
        $this->kernel->getAppContext()->userContext = new UserRequestContext(
            userId: current($request->getHeader('api-user-id')),
            tenantId: current($request->getHeader('api-tenant-id'))
        );

        return $next($request);
    }
}