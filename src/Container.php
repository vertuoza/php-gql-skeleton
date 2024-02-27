<?php

namespace src;

use Overblog\PromiseAdapter\Adapter\ReactPromiseAdapter;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Repositories\Database\QueryBuilder;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;
use Vertuoza\Usecases\Settings\UnitTypes\UnitTypesFindManyUseCase;

class Container
{
    private array $services = [];
    private UserRequestContext $userRequestContext;

    public function __construct(UserRequestContext $userRequestContext)
    {
        $this->userRequestContext = $userRequestContext;
    }
    public static function create(): Container
    {
        return new static();
    }
    public function getDatabase()
    {
        return $this->getOrBuild('database', function () {
            return new QueryBuilder();
        });
    }

    public function getDataloaderPromiseAdapter()
    {

        return $this->getOrBuild('DataloaderPromise', function () {
            return new ReactPromiseAdapter();
        });
    }

    public function getUnitTypeRepository()
    {
        return $this->getOrBuild('UnitTypeRepository', function () {
            return new UnitTypeRepository(
                $this->getDatabase(),
                $this->getDataloaderPromiseAdapter()
            );
        });
    }

    public function getUnitTypesFindManyUseCase()
    {
        return $this->getOrBuild('UnitTypesFindManyUseCase', function () {
            return new UnitTypesFindManyUseCase(
                $this->getUnitTypeRepository(),
                $this->userRequestContext
            );
        });
    }

    protected function getOrBuild($service, \Closure $builder)
    {
        if (!isset($this->services[$service])) {
            $this->services[$service] = $builder();
        }

        return $this->services[$service];
    }
}