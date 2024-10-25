<?php

namespace Vertuoza\Middleware;

use Exception;
use GraphQL\Executor\Promise\Promise;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use React\Promise\PromiseInterface;
use Vertuoza\Entities\CollaboratorEntity;
use Vertuoza\Entities\TenantEntity;
use Vertuoza\Entities\UserRequestContext;
use Vertuoza\Enums\FindTypeEum;
use Vertuoza\Repositories\CollaboratorRepository;
use Vertuoza\Repositories\TenantRepository;
use function React\Promise\resolve;

class ValidationUserContextMiddleware
{
    public function __construct(
        protected TenantRepository $tenantRepository,
        protected CollaboratorRepository $collaboratorRepository,
        protected UserRequestContext $userRequestContext
    ) {}

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
            $this->tenantRepository->find($this->userRequestContext->getTenantId(), FindTypeEum::FIND_OR_FAILED);
            $this->collaboratorRepository->find($this->userRequestContext->getUserId(), FindTypeEum::FIND_OR_FAILED);
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
