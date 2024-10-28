<?php

namespace Vertuoza\Interface\Exception;

interface ProvidesExceptionArgsInterface
{
    /**
     * Data added to define the exception
     *
     * @return array<string, mixed>|null
     */
    public function getArgs(): ?array;
}
