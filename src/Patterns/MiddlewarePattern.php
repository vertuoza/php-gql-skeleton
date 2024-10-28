<?php

namespace Vertuoza\Patterns;

use ArrayObject;
use Error;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use React\Http\Message\ServerRequest;
use React\Promise\PromiseInterface;
use Vertuoza\Context\AppContext;
use Vertuoza\Context\LogContext;
use Vertuoza\Context\UserRequestContext;
use Vertuoza\Entities\CollaboratorEntity;
use Vertuoza\Kernel;
use function React\Promise\resolve;

readonly abstract class MiddlewarePattern
{
    /**
     * @param Kernel $kernel
     */
    public function __construct(
        protected Kernel $kernel
    ) {}

    /**
     * @param ServerRequest $request
     * @param ?callable $next
     *
     * @return mixed
     */
    public function __invoke(ServerRequest $request, ?callable $next = null): mixed
    {
        try {
            if (!$this->withError() && $this->getErrors($request)->count()) {
                return $next($request);
            }

            if (!$this->isSupported($request)) {
                return $next($request);
            }

            return $this->handle($request, $next)->catch(function ($error) use ($request, $next) {
                $this->addErrors($request, $error);
                if ($next) {
                    return $next($request);
                } else {
                    return resolve(new Response(500));
                }
            });
        }catch (Exception|Error $e) {
            $this->addErrors($request, $e);
            if ($next) {
                return $next($request);
            } else {
                $this->kernel->getApplicationLogger()->error(
                    $e->getMessage()
                );
                return resolve(new Response(500));
            }
        }
    }

    public function addErrors(ServerRequest $request, Exception|Error $error): static
    {
        $errors = $this->getErrors($request);
        $errors->append($error);

        return $this;
    }

    /**
     * @param ServerRequest $request
     * @return ArrayObject
     */
    protected function getErrors(ServerRequest $request): ArrayObject
    {
        return $request->getAttribute('errors', new ArrayObject());
    }

    protected function withError(): bool
    {
        return false;
    }

    /**
     * @param ServerRequest $request
     * @param ?callable $next
     *
     * @return mixed
     */
    abstract protected function handle(ServerRequest $request, ?callable $next): PromiseInterface;

    /**
     * @param ServerRequest $request
     *
     * @return bool
     */
    public function isSupported(ServerRequest $request): bool
    {
        return true;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return UserRequestContext
     *
     * @throws Exception
     */
    public function getUserRequestContext(ServerRequestInterface $request): UserRequestContext
    {
        $userRequestContext = $request->getAttribute('user-context');

        if (!$userRequestContext instanceof UserRequestContext) {
            throw new Exception();
        }

        return $userRequestContext;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return LogContext
     *
     * @throws Exception
     */
    public function getLogContext(ServerRequestInterface $request): LogContext
    {
        $logContext = $request->getAttribute('log-context');

        if (!$logContext instanceof LogContext) {
            throw new Exception();
        }

        return $logContext;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return AppContext
     *
     * @throws Exception
     */
    public function getAppContext(ServerRequestInterface $request): AppContext
    {
        $appContext = $request->getAttribute('app-context');

        if (!$appContext instanceof AppContext) {
            throw new Exception();
        }

        return $appContext;
    }

    public function getUser(ServerRequestInterface $request): ?CollaboratorEntity
    {
        $authorization = $request->getHeader('Authorization');

        if (!$authorization) {
            return null;
        }

        list($basic, $base64) = explode(' ', $authorization[0]);

        $authDetails = explode(':', base64_decode($base64));
        $username = array_shift($authDetails);
        $password = implode(':', $authDetails);

        $collaboratorRepository = $this->kernel->getRepository(CollaboratorEntity::class);
        $user = $collaboratorRepository->findBy(['username' => $username])->first();

        if (!password_verify($password, $user->password)) {
            return null;
        }

        return $user;
    }
}