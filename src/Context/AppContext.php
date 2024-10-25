<?php

namespace Vertuoza\Context;

use Psr\Http\Message\ServerRequestInterface;
use Vertuoza\Factories\UseCasesFactory;

readonly class AppContext
{
    /**
     * @param ServerRequestInterface $request
     * @param UseCasesFactory $useCases
     */
    public function __construct(
        public ServerRequestInterface $request,
        public UseCasesFactory $useCases
    ) {}
}
