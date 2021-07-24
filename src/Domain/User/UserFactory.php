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

use FlexPHP\Bundle\HelperBundle\Domain\Helper\FactoryExtendedTrait;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\UserStatusFactory;

final class UserFactory
{
    use FactoryExtendedTrait;

    public function make($data): User
    {
        $user = new User();

        if (\is_object($data)) {
            $data = (array)$data;
        }

        if (isset($data['id'])) {
            $user->setId((int)$data['id']);
        }

        if (isset($data['email'])) {
            $user->setEmail((string)$data['email']);
        }

        if (isset($data['name'])) {
            $user->setName((string)$data['name']);
        }

        if (isset($data['password'])) {
            $user->setPassword((string)$data['password']);
        }

        if (isset($data['timezone'])) {
            $user->setTimezone((string)$data['timezone']);
        }

        if (isset($data['statusId'])) {
            $user->setStatusId((string)$data['statusId']);
        }

        if (isset($data['lastLoginAt'])) {
            $user->setLastLoginAt(\is_string($data['lastLoginAt']) ? new \DateTime($data['lastLoginAt']) : $data['lastLoginAt']);
        }

        if (isset($data['createdAt'])) {
            $user->setCreatedAt(\is_string($data['createdAt']) ? new \DateTime($data['createdAt']) : $data['createdAt']);
        }

        if (isset($data['updatedAt'])) {
            $user->setUpdatedAt(\is_string($data['updatedAt']) ? new \DateTime($data['updatedAt']) : $data['updatedAt']);
        }

        if (isset($data['createdBy'])) {
            $user->setCreatedBy((int)$data['createdBy']);
        }

        if (isset($data['updatedBy'])) {
            $user->setUpdatedBy((int)$data['updatedBy']);
        }

        if (isset($data['statusId.id'])) {
            $user->setStatusIdInstance((new UserStatusFactory())->make($this->getFkEntity('statusId.', $data)));
        }

        if (isset($data['createdBy.id'])) {
            $user->setCreatedByInstance((new UserFactory())->make($this->getFkEntity('createdBy.', $data)));
        }

        if (isset($data['updatedBy.id'])) {
            $user->setUpdatedByInstance((new UserFactory())->make($this->getFkEntity('updatedBy.', $data)));
        }

        return $user;
    }
}
