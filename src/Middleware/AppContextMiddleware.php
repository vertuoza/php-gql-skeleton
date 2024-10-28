<?php

namespace Vertuoza\Middleware;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Promise\PromiseInterface;
use Vertuoza\Context\AppContext;
use Vertuoza\Factories\UseCasesFactory;
use Vertuoza\Patterns\MiddlewarePattern;

readonly class AppContextMiddleware extends MiddlewarePattern
{
    /**
     * @param string $cookieName
     * @param string $cookieValue
     * @param int $exp
     * @param string $domain
     * @param string $path
     * @param bool $secure
     * @param bool $httpOnly
     * @param string $sameSite
     * @return void
     *
     * @todo use cookie
     * @todo maybe create class for cookie
     */
    public function addCookie(
        string $cookieName,
        string $cookieValue,
        int $exp = 0,
        string $domain = "",
        string $path = "/",
        bool $secure = false,
        bool $httpOnly = false,
        string $sameSite = "Lax"
    ): void
    {
        $expires = gmdate('D, d M Y H:i:s T', $exp); // Converting the expiration time to the correct format
        $path = "/";

        $cookieHeader = "$cookieName=$cookieValue; Expires=$expires; Path=$path; SameSite=$sameSite";
        if ($httpOnly) {
            $cookieHeader .= "; HttpOnly";
        }
        if ($secure) {
            $cookieHeader .= "; Secure";
        }
        if ($domain !== "") {
            $cookieHeader .= " Domain=$domain;";
        }

        //$this->headerContext[] = ["Set-Cookie" => $cookieHeader];
    }

    /**
     * @param ServerRequestInterface $request
     * @param ?callable $next
     *
     * @return mixed
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request, ?callable $next): PromiseInterface
    {
        return $next(
            $request->withAttribute(
                'app-context',
                $this->kernel->getFactory(AppContext::class)
                    ->addConstructorArgument('request', $request)
                    ->addConstructorArgument('useCases', new UseCasesFactory())
                    ->addConstructorArgument('user', $this->getUser($request))
                    ->newInstance()
            )
        )->then(function (ResponseInterface $response) {
            /*foreach ($this->headers as $header) {
                foreach ($header as $name => $value) {
                    $response = $response->withHeader($name, $value);
                }
            }*/

            $this->kernel->getDatabase()->getConnection()->disconnect();
            return $response;
        });
    }
}
