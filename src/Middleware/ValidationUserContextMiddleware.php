<?php

namespace Vertuoza\Middleware;

use Exception;
use GraphQL\Executor\Promise\Promise;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use React\Promise\PromiseInterface;
use Vertuoza\Enums\FindTypeEum;
use Vertuoza\Repositories\CollaboratorRepository;
use Vertuoza\Repositories\TenantRepository;
use function React\Promise\resolve;

readonly class ValidationUserContextMiddleware extends AbstractMiddleware
{
    /**
     * @param TenantRepository $tenantRepository
     * @param CollaboratorRepository $collaboratorRepository
     * @param ...$args
     */
    public function __construct(
        protected TenantRepository $tenantRepository,
        protected CollaboratorRepository $collaboratorRepository,
        ...$args
    ) {
        parent::__construct(...$args);
    }

    /**
     * @param ServerRequestInterface $request
     * @param callable $next
     *
     * @return PromiseInterface|Promise
     *
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request, callable $next): PromiseInterface|Promise
    {
        try {
            $this->tenantRepository->find(
                $this->getUserRequestContext($request)->getTenantId(),
                FindTypeEum::FIND_OR_FAILED
            );
            $this->collaboratorRepository->find(
                $this->getUserRequestContext($request)->getUserId(),
                FindTypeEum::FIND_OR_FAILED
            );
        } catch (Exception $e) {
            $response = Response::json(
                [
                    'message' => 'invalid user context'
                ]
            );
            return resolve($response);
        }

        return $next($request);
    }
}
