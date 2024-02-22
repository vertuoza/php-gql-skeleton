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
use Vertuoza\Usecases\Settings\Collaborators\CollaboratorByIdUseCase;

use function React\Async\await;
use function React\Promise\resolve;

class CollaboratorByIdUseCaseTest extends TestCase
{
    use ProphecyTrait;

    /**
     * Check the resolver result when the record exists.
     * @throws InvalidArgumentException
     */
    public function testResultWhenRecordExists(): void
    {
        // Prepare the resolver & its dependencies
        $repository = $this->prophesize(CollaboratorRepository::class);

        $repositories = $this->getMockBuilder(RepositoriesFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repositories->collaborator = $repository->reveal();

        $useCase = new CollaboratorByIdUseCase($repositories, new UserRequestContext('user-id', 'tenant-id'));

        // Request input
        $id = 'collaborator-id';

        // Expected repository calls
        $repository->getByIdAndTenantId($id, 'tenant-id')
            ->willReturn(resolve(new CollaboratorEntity('Name', 'First name', 'collaborator-id')))
            ->shouldBeCalledOnce();

        // Run the query
        $result = await($useCase->handle($id));

        // Check the result
        $this->assertInstanceOf(CollaboratorEntity::class, $result);
        $this->assertSame('collaborator-id', $result->id);
        $this->assertSame('Name', $result->name);
        $this->assertSame('First name', $result->firstName);
    }

    /**
     * Check the resolver result when the record does not exist.
     * @throws InvalidArgumentException
     */
    public function testResultWhenRecordDoesNotExist(): void
    {
        // Prepare the resolver & its dependencies
        $repository = $this->prophesize(CollaboratorRepository::class);

        $repositories = $this->getMockBuilder(RepositoriesFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repositories->collaborator = $repository->reveal();

        $useCase = new CollaboratorByIdUseCase($repositories, new UserRequestContext('user-id', 'tenant-id'));

        // Request input
        $id = 'collaborator-id';

        // Expected repository calls
        $repository->getByIdAndTenantId($id, 'tenant-id')
            ->willReturn(resolve(null))
            ->shouldBeCalledOnce();

        // Run the query
        $result = await($useCase->handle($id));

        // Check the result
        $this->assertNull($result);
    }

    /**
     * Check the resolver result when the request does not contain a tenant ID. It should behave
     * like the record does not exist.
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

        $useCase = new CollaboratorByIdUseCase($repositories, new UserRequestContext('user-id', null));

        // Request input
        $id = 'collaborator-id';

        // Expected repository calls
        $repository->getByIdAndTenantId(Argument::any(), Argument::any())->shouldNotBeCalled();

        // Run the query
        $result = await($useCase->handle($id));

        // Check the result
        $this->assertNull($result);
    }
}
