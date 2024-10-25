<?php

namespace Vertuoza\Logger;

readonly class LogContext
{
    /**
     * @param string|null $tenantId
     * @param string|null $userId
     */
    public function __construct(
        public string|null $tenantId = null,
        public string|null $userId = null
    ) {}
}
