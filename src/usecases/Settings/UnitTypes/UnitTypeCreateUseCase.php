<?php

namespace Vertuoza\Usecases\Settings\UnitTypes;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Libs\Exceptions\BadUserInputException;
use Vertuoza\Libs\Exceptions\Validators\StringValidator;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;

class UnitTypeCreateUseCase
{
    private UnitTypeRepository $unitTypeRepository;
    private UserRequestContext $userContext;

    public function __construct(
        RepositoriesFactory $repositories,
        UserRequestContext $userContext
    ) {
        $this->unitTypeRepository = $repositories->unitType;
        $this->userContext = $userContext;
    }

    /**
     * @param string $name
     * @return Promise
     * @throws BadUserInputException
     */
    public function handle(string $name): Promise
    {
        $errors = (new StringValidator('name', $name))
            ->notEmpty(true)
            ->max(255)
            ->min(1)
            ->matches(
                "/^[\p{L}\p{N} _'().\/-]+$/u",
                'Name should only contain letters, numbers, spaces, apostrophes, parentheses, periods, and hyphens'
            )
            ->validate();

        if (!empty($errors)) {
            throw new BadUserInputException($errors, 'name');
        }

        $data = new UnitTypeMutationData();
        $data->name = $name;

        $newId = $this->unitTypeRepository->create($data, $this->userContext->getTenantId());

        return $this->unitTypeRepository->getById((string)$newId, $this->userContext->getTenantId());
    }
}
