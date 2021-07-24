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

use FlexPHP\Bundle\UserBundle\Domain\UserStatus\Request\DeleteUserStatusRequest;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\Response\DeleteUserStatusResponse;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\UserStatusRepository;

final class DeleteUserStatusUseCase
{
    private UserStatusRepository $userStatusRepository;

    public function __construct(UserStatusRepository $userStatusRepository)
    {
        $this->userStatusRepository = $userStatusRepository;
    }

    public function execute(DeleteUserStatusRequest $request): DeleteUserStatusResponse
    {
        return new DeleteUserStatusResponse($this->userStatusRepository->remove($request));
    }
}
