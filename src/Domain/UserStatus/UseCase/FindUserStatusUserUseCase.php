<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\UserBundle\Domain\UserStatus\UseCase;

use FlexPHP\Bundle\UserBundle\Domain\UserStatus\Request\FindUserStatusUserRequest;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\Response\FindUserStatusUserResponse;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\UserStatusRepository;

final class FindUserStatusUserUseCase
{
    private UserStatusRepository $userStatusRepository;

    public function __construct(UserStatusRepository $userStatusRepository)
    {
        $this->userStatusRepository = $userStatusRepository;
    }

    public function execute(FindUserStatusUserRequest $request): FindUserStatusUserResponse
    {
        $users = $this->userStatusRepository->findUsersBy($request);

        return new FindUserStatusUserResponse($users);
    }
}
