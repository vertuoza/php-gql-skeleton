<?php

declare(strict_types=1);

namespace Vertuoza\Tests\UseCases\Settings\Collaborators;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\CollaboratorEntity;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\Collaborators\CollaboratorRepository;
use Vertuoza\Usecases\Settings\Collaborators\CollaboratorsFindManyUseCase;

use function React\Async\await;
use function React\Promise\resolve;

class CollaboratorsFindManyUseCaseTest extends TestCase
{
    use ProphecyTrait;

    /**
     * Check the result when the repository returned rows.
     * @throws InvalidArgumentException
     */
    public function testResultWhenRowsExist(): void
    {
        // Prepare the resolver & its dependencies
        $repository = $this->prophesize(CollaboratorRepository::class);

        $repositories = $this->getMockBuilder(RepositoriesFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repositories->collaborator = $repository->reveal();

        $useCase = new CollaboratorsFindManyUseCase($repositories, new UserRequestContext('user-id', 'tenant-id'));

        // Expected repository calls
        $repository->getByTenantId('tenant-id')
            ->willReturn(resolve([
                new CollaboratorEntity('Name', 'First name', 'collaborator-id'),
                new CollaboratorEntity('Name2', 'First name2', 'collaborator-id2'),
            ]))
            ->shouldBeCalledOnce();

        // Run the query
        $result = await($useCase->handle());

        // Check the result
        $this->assertCount(2, $result);
        $this->assertInstanceOf(CollaboratorEntity::class, $result[0]);
    }

    /**
     * Check the result when the repository returned no rows.
     * @throws InvalidArgumentException
     */
    public function testResultWhenNoRowsExist(): void
    {
        // Prepare the resolver & its dependencies
        $repository = $this->prophesize(CollaboratorRepository::class);

        $repositories = $this->getMockBuilder(RepositoriesFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repositories->collaborator = $repository->reveal();

        $useCase = new CollaboratorsFindManyUseCase($repositories, new UserRequestContext('user-id', 'tenant-id'));

        // Expected repository calls
        $repository->getByTenantId('tenant-id')
            ->willReturn(resolve([]))
            ->shouldBeCalledOnce();

        // Run the query
        $result = await($useCase->handle());

        // Check the result
        $this->assertCount(0, $result);
    }

    /**
     * Check the resolver result when the request does not contain a tenant ID. It should behave
     * like there are no records.
     * @throws InvalidArgumentException
     */
    public function testRequestWithoutTenantId(): void
    {
        // Prepare the resolver & its dependencies
        $repository = $this->prophesize(CollaboratorRepository::class);

        $repositories = $this->getMockBuilder(RepositoriesFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repositories->collaborator = $repository->reveal();

        $useCase = new CollaboratorsFindManyUseCase($repositories, new UserRequestContext('user-id', null));

        // Expected repository calls
        $repository->getByTenantId(Argument::any())->shouldNotBeCalled();

        // Run the query
        $result = await($useCase->handle());

        // Check the result
        $this->assertCount(0, $result);
    }
}
