<?php

declare(strict_types=1);

namespace Vertuoza\Tests\UseCases\Settings\UnitTypes;

use Exception;
use GraphQL\Error\Error;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Vertuoza\Api\Graphql\Context\UserRequestContext;
use Vertuoza\Entities\Settings\UnitTypeEntity;
use Vertuoza\Repositories\RepositoriesFactory;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeMutationData;
use Vertuoza\Repositories\Settings\UnitTypes\UnitTypeRepository;
use Vertuoza\Usecases\Settings\UnitTypes\UnitTypeCreateUseCase;

use function React\Async\await;
use function React\Promise\resolve;

class UnitTypeCreateUseCaseTest extends TestCase
{
    use ProphecyTrait;

    /**
     * Check the mutation result when the input is valid.
     * @throws Exception
     */
    public function testAcceptsValidInput(): void
    {
        // Prepare the resolver & its dependencies
        $repository = $this->prophesize(UnitTypeRepository::class);

        $repositories = $this->getMockBuilder(RepositoriesFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repositories->unitType = $repository->reveal();

        $useCase = new UnitTypeCreateUseCase($repositories, new UserRequestContext('user-id', 'tenant-id'));

        // Request input
        $mutationData = new UnitTypeMutationData(name: 'Valid name');

        // Expected repository calls
        $repository->create($mutationData, 'tenant-id')
            ->willReturn('unit-type-id')
            ->shouldBeCalledOnce();
        $repository->getById('unit-type-id', 'tenant-id')
            ->willReturn(resolve(new UnitTypeEntity('unit-type-id', 'Valid name')))
            ->shouldBeCalledOnce();

        // Run the mutation
        $result = await($useCase->handle($mutationData));

        // Check the result
        $this->assertInstanceOf(UnitTypeEntity::class, $result);
        $this->assertSame('unit-type-id', $result->id);
        $this->assertSame('Valid name', $result->name);
        $this->assertFalse($result->isSystem);
    }

    /**
     * Check the mutation result when the input is invalid. It should throw GraphQL compatible error.
     * @throws Exception
     */
    public function testRejectsInvalidInput(): void
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessage('Input data is invalid');

        // Prepare the resolver & its dependencies
        $repository = $this->prophesize(UnitTypeRepository::class);

        $repositories = $this->getMockBuilder(RepositoriesFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repositories->unitType = $repository->reveal();

        $useCase = new UnitTypeCreateUseCase($repositories, new UserRequestContext('user-id', 'tenant-id'));

        // Request input
        $mutationData = new UnitTypeMutationData(name: '');

        // Expected repository calls
        $repository->create(Argument::any(), Argument::any())->shouldNotBeCalled();

        // Run the mutation
        await($useCase->handle($mutationData));
    }

    /**
     * Check the mutation result when the the context does not have a tenant id.
     * It should throw GraphQL compatible error.
     * @throws Exception
     */
    public function testRejectsRequestsWithoutTenantId(): void
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessage('The method does not support creation of system unit types');

        // Prepare the resolver & its dependencies
        $repository = $this->prophesize(UnitTypeRepository::class);

        $repositories = $this->getMockBuilder(RepositoriesFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repositories->unitType = $repository->reveal();

        $useCase = new UnitTypeCreateUseCase($repositories, new UserRequestContext('user-id', null));

        // Request input
        $mutationData = new UnitTypeMutationData(name: 'Valid name');

        // Expected repository calls
        $repository->create(Argument::any(), Argument::any())->shouldNotBeCalled();

        // Run the mutation
        await($useCase->handle($mutationData));
    }
}
