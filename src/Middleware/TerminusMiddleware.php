<?php

namespace Vertuoza\Middleware;

use Exception;
use GraphQL\Error\Error;
use GraphQL\Error\InvariantViolation;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use React\Promise\PromiseInterface;
use Vertuoza\Errors\GqlClientError;
use Vertuoza\Patterns\MiddlewarePattern;
use function React\Promise\resolve;

readonly class TerminusMiddleware extends MiddlewarePattern
{
    /**
     * @param ServerRequestInterface $request
     * @param ?callable $next
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request, ?callable $next = null): PromiseInterface
    {
        $errors = $this->getErrors($request);

        if ($errors->count()) {
            $this->kernel->getApplicationLogger()->error(
                'An error has occurred',
                metadata: $errors->getArrayCopy()
            );

            $error = current($errors->getArrayCopy());
            switch (true) {
                case ($error instanceof InvariantViolation):
                case ($error instanceof Error):
                    $gqlErrors = $this->graphQLErrorsHandler($errors->getArrayCopy());

                    $response = new Response(
                        $gqlErrors[0]->getCode() ?: ($gqlErrors[0]?->getExtensions()['code'] ?? 500),
                        body: json_encode(['errors' => $gqlErrors], JSON_THROW_ON_ERROR)
                    );
                    break;
                default:
                    $response = new Response(
                        500,
                        body: json_encode(
                            ["errors" => [['Internal Server Error']]]
                        )
                    );
                    break;
            };
        }

        return resolve($response ?? new Response(500));
    }

    /**
     * @param array $errors
     *
     * @return array<Error>
     */
    public function graphQLErrorsHandler(array $errors): array
    {
        $this->kernel->getApplicationLogger()->error(
            'An error has occurred',
            metadata: $errors
        );
        return $this->kernel->getServerConfig()->getErrorsHandler()(
            $errors,
            $this->kernel->getServerConfig()->getErrorFormatter()
        );
    }

    public function withError(): bool
    {
        return true;
    }
}