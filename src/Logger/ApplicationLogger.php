<?php

namespace Vertuoza\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LogLevel;
use Stringable;
use Vertuoza\Enums\ExceptionErrorCodeEnum;
use Vertuoza\Patterns\SingletonPattern;

class ApplicationLogger extends SingletonPattern
{
    private Logger $logger;
    private string $serviceName;

    /**
     * @return ApplicationLogger
     */
    protected static function newInstance(): static
    {
        $instance = new static();

        $instance->serviceName = 'gql-api';
        $instance->logger = new Logger('kernel');
        if (isset($_ENV['LOG_ENABLED']) && $_ENV['LOG_ENABLED'] === 'true') {
            $stream = new StreamHandler('php://stdout', LogLevel::DEBUG);
            $formatter = new GcpLoggerFormatter(['serviceName', 'tenantId']);
            $formatter->setJsonPrettyPrint(isset($_ENV['MODE']) && $_ENV['MODE'] === 'DEVELOPMENT');

            $stream->setFormatter($formatter);

            $instance->logger->pushHandler($stream);
        }

        return $instance;
    }

    /**
     * @param LogContext|null $logContext
     * @param array $metadata
     *
     * @return array|string[]
     */
    protected function computeContext(LogContext|null $logContext, array $metadata = []): array
    {
        $metadata['serviceName'] = $this->serviceName;

        if ($logContext === null) {
            return $metadata;
        }

        if ($logContext->tenantId !== null) {
            $metadata['tenantId'] = $logContext->tenantId;
        }

        if ($logContext->userId !== null) {
            $metadata['userId'] = $logContext->userId;
        }

        return $metadata;
    }

    /**
     * @param Stringable|string $message
     * @param LogContext|null $logContext
     * @param array $metadata
     *
     * @return void
     */
    public function info(
        Stringable|string $message,
        LogContext|null $logContext,
        array $metadata = []
    ): void
    {
        $this->logger->info($message, $this->computeContext($logContext, $metadata));
    }

    /**
     * @param Stringable|string $message
     * @param LogContext|null $logContext
     * @param array $metadata
     *
     * @return void
     */
    public function debug(
        Stringable|string $message,
        LogContext|null $logContext,
        array $metadata = []
    ): void
    {
        $this->logger->debug($message, $this->computeContext($logContext, $metadata));
    }

    /**
     * @param Stringable|string $message
     * @param LogContext|null $logContext
     * @param array $metadata
     *
     * @return void
     */
    public function warning(
        Stringable|string $message,
        LogContext|null $logContext,
        array $metadata = []
    ): void
    {
        $this->logger->warning($message, $this->computeContext($logContext, $metadata));
    }

    /**
     * @param Stringable|string $message
     * @param ExceptionErrorCodeEnum $exceptionErrorCodeEnum
     * @param LogContext|null $logContext
     * @param array $metadata
     *
     * @return void
     */
    public function error(
        Stringable|string $message,
        ExceptionErrorCodeEnum $exceptionErrorCodeEnum,
        LogContext|null $logContext = null,
        array $metadata = []
    ): void
    {
        $this->logger->error(
            $message,
            array_merge(
                [
                    'errorCode' => $exceptionErrorCodeEnum->name,
                    'statusCode' => $exceptionErrorCodeEnum->value
                ],
                $this->computeContext($logContext, $metadata)
            )
        );
    }

    /**
     * @param Stringable|string $message
     * @param LogContext|null $logContext
     * @param array $metadata
     *
     * @return void
     */
    public function critical(
        Stringable|string $message,
        LogContext|null $logContext = null,
        array $metadata = []
    ): void
    {
        $this->logger->critical($message, $this->computeContext($logContext, $metadata));
    }

    /**
     * @param Stringable|string $message
     * @param LogContext|null $logContext
     * @param array $metadata
     *
     * @return void
     */
    public function emergency(
        Stringable|string $message,
        LogContext|null $logContext,
        array $metadata = []
    ): void
    {
        $this->logger->emergency($message, $this->computeContext($logContext, $metadata));
    }
}
