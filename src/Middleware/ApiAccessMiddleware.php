<?php

namespace Vertuoza\Middleware;

use Closure;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use React\Promise\PromiseInterface;
use Vertuoza\Patterns\MiddlewarePattern;
use function React\Promise\resolve;

readonly class ApiAccessMiddleware extends MiddlewarePattern
{
    /**
     * @param array $config
     * @param ...$args
     */
    public function __construct(
        protected array $config,
        ...$args
    ) {
        parent::__construct(...$args);
    }

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
        if (current($request->getHeader('api-key')) != $this->config['key']) {
            $response = Response::json(
                [
                    'message' => 'invalid key'
                ]
            );
            return resolve($response);
        }

        return $next($request);
    }
}
