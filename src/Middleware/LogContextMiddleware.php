<?php

namespace Vertuoza\Middleware;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Vertuoza\Context\LogContext;
use Vertuoza\Context\UserRequestContext;
use Vertuoza\Kernel;

readonly class LogContextMiddleware extends AbstractMiddleware
{
    /**
     * @param ServerRequestInterface $request
     * @param callable $next
     * @return mixed
     *
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request, callable $next): mixed
    {
        $userContext = $this->getUserRequestContext($request);
        $logContext = $this->kernel->getFactory(LogContext::class)
            ->addConstructorArgument('userId', $userContext->getUserId())
            ->addConstructorArgument('tenantId', $userContext->getTenantId())
            ->newInstance();

        $this->kernel->getApplicationLogger()->setLogContext($logContext);

        return $next($request->withAttribute(
            'log-context',
            $logContext
        ));
    }
}