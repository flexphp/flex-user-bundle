<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\UserBundle\Domain\UserStatus;

use FlexPHP\Bundle\UserBundle\Domain\UserStatus\Request\CreateUserStatusRequest;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\Request\DeleteUserStatusRequest;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\Request\IndexUserStatusRequest;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\Request\ReadUserStatusRequest;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\Request\UpdateUserStatusRequest;

final class UserStatusRepository
{
    private UserStatusGateway $gateway;

    public function __construct(UserStatusGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return array<UserStatus>
     */
    public function findBy(IndexUserStatusRequest $request): array
    {
        return \array_map(function (array $userStatus) {
            return (new UserStatusFactory())->make($userStatus);
        }, $this->gateway->search((array)$request, [], $request->_page, $request->_limit, $request->_offset));
    }

    public function add(CreateUserStatusRequest $request): UserStatus
    {
        $userStatus = (new UserStatusFactory())->make($request);

        $userStatus->setId($this->gateway->push($userStatus));

        return $userStatus;
    }

    public function getById(ReadUserStatusRequest $request): UserStatus
    {
        $factory = new UserStatusFactory();
        $data = $this->gateway->get($factory->make($request));

        return $factory->make($data);
    }

    public function change(UpdateUserStatusRequest $request): UserStatus
    {
        $userStatus = (new UserStatusFactory())->make($request);

        $this->gateway->shift($userStatus);

        return $userStatus;
    }

    public function remove(DeleteUserStatusRequest $request): UserStatus
    {
        $factory = new UserStatusFactory();
        $data = $this->gateway->get($factory->make($request));

        $userStatus = $factory->make($data);

        $this->gateway->pop($userStatus);

        return $userStatus;
    }
}
