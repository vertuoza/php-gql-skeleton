<?php

namespace Vertuoza\Repositories\Settings\UnitTypes;

class UnitTypeMutationData
{
  public string $name;

    public function __construct(
        string $name
    ) {
        $this->name = $name;
    }
}
