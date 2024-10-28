<?php

namespace Vertuoza\Middleware;

use ArrayObject;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use React\Promise\PromiseInterface;
use Vertuoza\Patterns\MiddlewarePattern;

readonly class ErrorContextMiddleware extends MiddlewarePattern
{
    /**
     * @param ServerRequestInterface $request
     * @param ?callable $next
     *
     * @return mixed
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request, ?callable $next): PromiseInterface
    {
        return $next(
            $request->withAttribute(
                'errors',
                new ArrayObject()
            )
        );
    }
}
