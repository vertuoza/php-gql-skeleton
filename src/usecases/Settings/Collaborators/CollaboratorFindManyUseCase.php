<?php

namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\CollaboratorEntity;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;

class CollaboratorFindManyUseCase
{
	private UserRequestContext $userContext;
	private CollaboratorRepository $collaboratorRepository;

	public function __construct(
		RepositoriesFactory $repositories,
		UserRequestContext $userContext,
	) {
		$this->collaboratorRepository = $repositories->collaborator;
		$this->userContext = $userContext;
	}

	/**
	 * @return Promise<CollaboratorEntity>
	 */
	public function handle()
	{
		return $this->collaboratorRepository->findMany($this->userContext->getTenantId());
	}
}
