<?php
namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Repositories\RepositoriesFactory;

class CollaboratorGetAllUseCase
{
  public function __construct(private RepositoriesFactory $repositories) {}

  public function handle(RequestContext $context)
  {
    return $this->repositories->collaborator->findMany(
      $context->userContext->getTenantId()
    );
  }
}