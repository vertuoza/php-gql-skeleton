<?php

namespace Vertuoza\Middleware;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use React\Promise\PromiseInterface;
use Vertuoza\Context\LogContext;
use Vertuoza\Patterns\MiddlewarePattern;

readonly class LogContextMiddlewarePattern extends MiddlewarePattern
{
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