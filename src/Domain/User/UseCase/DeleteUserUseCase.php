<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\UserBundle\Domain\User\UseCase;

use FlexPHP\Bundle\UserBundle\Domain\User\Request\DeleteUserRequest;
use FlexPHP\Bundle\UserBundle\Domain\User\Response\DeleteUserResponse;
use FlexPHP\Bundle\UserBundle\Domain\User\UserRepository;

final class DeleteUserUseCase
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(DeleteUserRequest $request): DeleteUserResponse
    {
        return new DeleteUserResponse($this->userRepository->remove($request));
    }
}
