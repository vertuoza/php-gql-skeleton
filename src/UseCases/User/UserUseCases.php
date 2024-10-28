<?php

namespace Vertuoza\UseCases\User;

use Vertuoza\Repositories\CollaboratorRepository;

readonly class UserUseCases
{
    /**
     * @var SubscribeUserCase
     */
    public SubscribeUserCase $subscribe;
    public LoginUserCase $login;

    public function __construct(CollaboratorRepository $collaboratorRepository)
    {
        $this->subscribe = new SubscribeUserCase($collaboratorRepository);
        $this->login = new LoginUserCase($collaboratorRepository);
    }
}
