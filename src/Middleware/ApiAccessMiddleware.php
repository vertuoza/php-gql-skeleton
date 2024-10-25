<?php

namespace Vertuoza\Middleware;

use Closure;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use Vertuoza\Kernel;
use function React\Promise\resolve;

readonly class ApiAccessMiddleware extends AbstractMiddleware
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
     * @param callable $next
     *
     * @return Closure
     *
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request, callable $next): mixed
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
