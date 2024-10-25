<?php

namespace Vertuoza\Errors;

use GraphQL\Error\Error;
use Vertuoza\Entities\UserRequestContext;
use Vertuoza\Enums\ExceptionErrorCodeEnum;
use Vertuoza\Exceptions\BadUserInputException;
use Vertuoza\Exceptions\BusinessExceptionInterface;
use Vertuoza\Exceptions\UnauthorizedTenantException;
use Vertuoza\Interface\Exception\ProvidesExceptionArgsInterface;
use Vertuoza\Logger\ApplicationLogger;
use Vertuoza\Logger\LogContext;

class GqlErrorHandler
{
    public static function handle(array $errors, ?callable $formatter, ?UserRequestContext $userContext)
    {
        $mapped = array_map(function ($error) use ($userContext) {
            $locatedError = null;

            $errorCode = ExceptionErrorCodeEnum::INTERNAL_SERVER_ERROR;

            if ($error instanceof Error) {
                $locatedError = $error;
                if (!$error->isClientSafe()) {
                    ApplicationLogger::getInstance()->error($error, $errorCode);
                }
            }
            $previous = $error->getPrevious();
            if ($previous != null && $previous instanceof BusinessExceptionInterface) {
                $errorCode = $previous->getErrorCode();
                $statusCode = $previous->getCode();
                $message = $previous->getMessage();


                $args = [];
                if ($previous instanceof ProvidesExceptionArgsInterface) {
                    $args = $previous->getArgs() ?? [];
                }

                $logContext = new LogContext($userContext?->getTenantId(), $userContext?->getUserId());
                ApplicationLogger::getInstance()->error($message, $errorCode, $logContext, $args, $statusCode);

                if ($previous instanceof UnauthorizedTenantException) {
                    $errorCode = "NOT_FOUND";
                    $message = "The resource is not found";
                }

                $fields = null;
                if ($previous instanceof BadUserInputException) {
                    $fieldErrors = array_merge($args, $previous->getFieldsError());
                    $fields = array_map(function ($fieldError) {
                        return $fieldError->toArray();
                    }, $fieldErrors);
                }

                if ($locatedError !== null) {
                    return new GqlClientError(
                        $message,
                        $errorCode,
                        $error->getNodes(),
                        $error->getSource(),
                        $error->getPositions(),
                        $error->getPath(),
                        $error->getPrevious(),
                        array_merge($args, ["type" => "BUSINESS_EXCEPTION", "statusCode" => $statusCode]),
                        isset($fields) ? $fields : null
                    );
                }
            }
            return $error;
        }, $errors);
        return array_map($formatter, $mapped);
    }
}
