<?php

namespace Vertuoza\UseCases\User;

use React\Promise\PromiseInterface;
use Vertuoza\Entities\CollaboratorEntity;
use Vertuoza\Entities\UnitTypeEntity;
use Vertuoza\Repositories\CollaboratorRepository;
use Vertuoza\Repositories\UnitTypeRepository;
use function React\Async\async;

class LoginUserCase
{
    public function __construct(
        protected CollaboratorRepository $collaboratorRepository
    )
    {}

    /**
     *
     * @return PromiseInterface<CollaboratorEntity>
     */
    public function handle(
        ...$args
    ): PromiseInterface
    {
        die('kk');
        return async(
            [$this->collaboratorRepository, 'create']
        )([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'lastname' => $lastname,
            'firstname' => $firstname
        ]);
    }
}
