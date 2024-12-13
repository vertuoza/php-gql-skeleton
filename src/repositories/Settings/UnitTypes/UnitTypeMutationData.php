<?php

namespace Vertuoza\Repositories\Settings\UnitTypes;

class UnitTypeMutationData
{
  public string $name;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
    }
}
