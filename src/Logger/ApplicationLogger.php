<?php

namespace Vertuoza\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LogLevel;
use Stringable;
use Vertuoza\Context\LogContext;
use Vertuoza\Enums\ExceptionErrorCodeEnum;
use Vertuoza\Formatter\GcpLoggerFormatter;
use Vertuoza\Patterns\SingletonPattern;

class ApplicationLogger extends SingletonPattern
{
    private Logger $logger;
    private string $serviceName;
    private LogContext|null $logContext = null;

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
     * @param array $metadata
     *
     * @return array|string[]
     */
    protected function computeContext(array $metadata = []): array
    {
        $metadata['serviceName'] = $this->serviceName;

        if ($this->logContext?->tenantId) {
            $metadata['tenantId'] = $this->logContext->tenantId;
        }

        if ($this->logContext?->userId) {
            $metadata['userId'] = $this->logContext->userId;
        }

        return $metadata;
    }

    /**
     * @param Stringable|string $message
     * @param array $metadata
     *
     * @return void
     */
    public function info(
        Stringable|string $message,
        array $metadata = []
    ): void
    {
        $this->logger->info($message, $this->computeContext($metadata));
    }

    /**
     * @param Stringable|string $message
     * @param array $metadata
     *
     * @return void
     */
    public function debug(
        Stringable|string $message,
        array $metadata = []
    ): void
    {
        $this->logger->debug($message, $this->computeContext($metadata));
    }

    /**
     * @param Stringable|string $message
     * @param array $metadata
     *
     * @return void
     */
    public function warning(
        Stringable|string $message,
        array $metadata = []
    ): void
    {
        $this->logger->warning($message, $this->computeContext($metadata));
    }

    /**
     * @param Stringable|string $message
     * @param ExceptionErrorCodeEnum $exceptionErrorCodeEnum
     * @param array $metadata
     *
     * @return void
     */
    public function error(
        Stringable|string $message,
        ExceptionErrorCodeEnum $exceptionErrorCodeEnum,
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
                $this->computeContext($metadata)
            )
        );
    }

    /**
     * @param Stringable|string $message
     * @param array $metadata
     *
     * @return void
     */
    public function critical(
        Stringable|string $message,
        array $metadata = []
    ): void
    {
        $this->logger->critical($message, $this->computeContext($metadata));
    }

    /**
     * @param Stringable|string $message
     * @param array $metadata
     *
     * @return void
     */
    public function emergency(
        Stringable|string $message,
        array $metadata = []
    ): void
    {
        $this->logger->emergency($message, $this->computeContext($metadata));
    }

    /**
     * @param LogContext|null $logContext
     * @return static
     */
    public function setLogContext(LogContext|null $logContext): static
    {
        $this->logContext = $logContext;

        return $this;
    }
}
