<?php
namespace Vertuoza\Usecases\Settings\Collaborators;

use Vertuoza\Api\Graphql\Context\RequestContext;
use Vertuoza\Libs\Exceptions\NotFoundException;
use Vertuoza\Repositories\RepositoriesFactory;

use function React\Async\async;
use function React\Async\await;

class CollaboratorGetByIdUseCase
{
  public function __construct(private RepositoriesFactory $repositories) {}

  public function handle(string $id, RequestContext $context)
  {
    return async(function () use ($id, $context) {
      $collaborator = await(
        $this->repositories->collaborator->getById($id, $context->userContext->getTenantId())
      );

      if (!$collaborator) {
        throw new NotFoundException("Collaborator with id {$id} not found");
      }

      return $collaborator;
    })();
  }
}