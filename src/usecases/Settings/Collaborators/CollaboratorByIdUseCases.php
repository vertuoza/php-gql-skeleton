<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use React\Promise\Promise;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\CollaboratorEntity;
use Vertuoza\Libs\Exceptions\BadUserInputException;
use Vertuoza\Libs\Exceptions\NotAuthorizedException;
use Vertuoza\Libs\Exceptions\Validators\StringValidator;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;

class CollaboratorByIdUseCases
{
	private CollaboratorRepository $collaboratorRepository;

	public function __construct(
		RepositoriesFactory $repositories,
		private UserRequestContext $userContext
	) {
		$this->collaboratorRepository = $repositories->collaborator;
	}

	/**
	 * @param string $id id of the collaborator to retrieve
	 * @return Promise<CollaboratorEntity>
	 * @throws BadUserInputException
	 * @throws NotAuthorizedException
	 */
	public function handle(string $id): Promise
	{
		if (!$tenantId = $this->userContext->getTenantId()) {
			throw new NotAuthorizedException();
		}
		(new StringValidator('id', $id))
			->notEmpty(true)
			->isUuid(true)
			->throwFirstError();
		return $this->collaboratorRepository->getById($id, $tenantId);
	}
}