<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\UserBundle\Domain\User;

use FlexPHP\Bundle\UserBundle\Domain\User\Request\CreateUserRequest;
use FlexPHP\Bundle\UserBundle\Domain\User\Request\DeleteUserRequest;
use FlexPHP\Bundle\UserBundle\Domain\User\Request\FindUserUserStatusRequest;
use FlexPHP\Bundle\UserBundle\Domain\User\Request\IndexUserRequest;
use FlexPHP\Bundle\UserBundle\Domain\User\Request\LoginUserRequest;
use FlexPHP\Bundle\UserBundle\Domain\User\Request\ReadUserRequest;
use FlexPHP\Bundle\UserBundle\Domain\User\Request\UpdateUserRequest;

final class UserRepository
{
    private UserGateway $gateway;

    public function __construct(UserGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return array<User>
     */
    public function findBy(IndexUserRequest $request): array
    {
        return \array_map(function (array $user) {
            return (new UserFactory())->make($user);
        }, $this->gateway->search((array)$request, [], $request->_page, $request->_limit, $request->_offset));
    }

    public function add(CreateUserRequest $request): User
    {
        $user = (new UserFactory())->make($request);

        if ($user->getPassword()) {
            $user->setPassword($this->getHashPassword($user->getPassword()));
        }

        $user->setId($this->gateway->push($user));

        return $user;
    }

    public function getById(ReadUserRequest $request): User
    {
        $factory = new UserFactory();
        $data = $this->gateway->get($factory->make($request));

        $data['password'] = $this->getFakePassword();

        return $factory->make($data);
    }

    public function change(UpdateUserRequest $request): User
    {
        $user = (new UserFactory())->make($request);

        if ($user->getPassword() && $user->getPassword() !== $this->getFakePassword()) {
            $user->setPassword($this->getHashPassword($user->getPassword()));
        }

        $this->gateway->shift($user);

        return $user;
    }

    public function remove(DeleteUserRequest $request): User
    {
        $factory = new UserFactory();
        $data = $this->gateway->get($factory->make($request));

        $user = $factory->make($data);

        $this->gateway->pop($user);

        return $user;
    }

    public function getByLogin(LoginUserRequest $request): User
    {
        $data = $this->gateway->getBy('email', $request->email);

        return (new UserFactory())->make($data);
    }

    private function getHashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    private function getFakePassword(): string
    {
        return '**********';
    }

    public function findUserStatusBy(FindUserUserStatusRequest $request): array
    {
        return $this->gateway->filterUserStatus($request, $request->_page, $request->_limit);
    }
}
