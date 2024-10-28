<?php

namespace Vertuoza\Handlers;

use GraphQL\Error\Error;
use GraphQL\Error\InvariantViolation;
use Vertuoza\Enums\ExceptionErrorCodeEnum;
use Vertuoza\Errors\GqlClientError;
use Vertuoza\Exceptions\BadUserInputException;
use Vertuoza\Exceptions\BusinessException;
use Vertuoza\Exceptions\UnauthorizedTenantException;
use Vertuoza\Logger\ApplicationLogger;

class GqlErrorHandler
{
    public function __construct(
        protected ApplicationLogger $applicationLogger
    )
    {}

    /**
     * @param array $errors
     * @param callable|null $formatter
     *
     * @return array
     */
    public function handle(array $errors, ?callable $formatter): array
    {
        return array_map(
            $formatter,
            array_map(
                [$this, 'resolveError'],
                $errors
            )
        );
    }

    public function resolveError(Error|InvariantViolation $error): Error|InvariantViolation
    {
        if (!$error instanceof Error) {
            $error = new Error(
                message: $error->getMessage()
            );
        }

        if (!$error->isClientSafe()) {
            $this->applicationLogger->error(
                $error,
                ExceptionErrorCodeEnum::INTERNAL_SERVER_ERROR
            );
        }

        $previous = $error->getPrevious();
        if (!$previous instanceof BusinessException) {
            return $error;
        }

        $errorCode = $previous->getErrorCode();
        $message = $previous->getMessage();
        $args = $previous->getArgs();

        $this->applicationLogger->error($message, $errorCode, $args);

        if ($previous instanceof UnauthorizedTenantException) {
            $errorCode = ExceptionErrorCodeEnum::NOT_FOUND;
            $message = "The resource is not found";
        }

        $fields = null;
        if ($previous instanceof BadUserInputException) {
            $fieldErrors = array_merge($args, $previous->getFieldsError());
            $fields = array_map(function ($fieldError) {
                return $fieldError->toArray();
            }, $fieldErrors);
        }

        $args['type'] = 'BUSINESS_EXCEPTION';

        return new GqlClientError(
            $message,
            $error->getNodes(),
            $error->getSource(),
            $error->getPositions(),
            $error->getPath(),
            $error->getPrevious(),
            $args,
            $fields,
            $errorCode
        );
    }
}
