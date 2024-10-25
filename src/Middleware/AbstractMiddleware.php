<?php

namespace Vertuoza\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Vertuoza\Context\AppContext;
use Vertuoza\Context\LogContext;
use Vertuoza\Context\UserRequestContext;
use Exception;
use Vertuoza\Kernel;

readonly abstract class AbstractMiddleware
{
    public function __construct(
        protected Kernel $kernel
    ) {}

    /**
     * @param ServerRequestInterface $request
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
}