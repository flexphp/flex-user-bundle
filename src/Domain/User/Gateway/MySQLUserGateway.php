<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\UserBundle\Domain\User\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use FlexPHP\Bundle\HelperBundle\Domain\Helper\DbalCriteriaHelper;
use FlexPHP\Bundle\UserBundle\Domain\User\Request\FindUserUserStatusRequest;
use FlexPHP\Bundle\UserBundle\Domain\User\User;
use FlexPHP\Bundle\UserBundle\Domain\User\UserGateway;

class MySQLUserGateway implements UserGateway
{
    private $conn;

    private $operator = [
        //
    ];

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function search(array $wheres, array $orders, int $page, int $limit, int $offset): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'user.Id as id',
            'user.Email as email',
            'user.Name as name',
            'user.Password as password',
            'user.Timezone as timezone',
            'user.StatusId as statusId',
            'user.LastLoginAt as lastLoginAt',
            'statusId.id as `statusId.id`',
            'statusId.name as `statusId.name`',
        ]);
        $query->from('`Users`', '`user`');
        $query->leftJoin('`user`', '`UserStatus`', '`statusId`', 'user.StatusId = statusId.id');

        $query->orderBy('user.UpdatedAt', 'DESC');

        $criteria = new DbalCriteriaHelper($query, $offset);

        foreach ($wheres as $column => $value) {
            $criteria->getCriteria('user', $column, $value, $this->operator[$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }

    public function push(User $user): int
    {
        $query = $this->conn->createQueryBuilder();

        $query->insert('`Users`');

        $query->setValue('Email', ':email');
        $query->setValue('Name', ':name');
        $query->setValue('Password', ':password');
        $query->setValue('Timezone', ':timezone');
        $query->setValue('StatusId', ':statusId');
        $query->setValue('LastLoginAt', ':lastLoginAt');
        $query->setValue('CreatedAt', ':createdAt');
        $query->setValue('UpdatedAt', ':updatedAt');
        $query->setValue('CreatedBy', ':createdBy');

        $query->setParameter(':email', $user->email(), DB::STRING);
        $query->setParameter(':name', $user->name(), DB::STRING);
        $query->setParameter(':password', $user->password(), DB::STRING);
        $query->setParameter(':timezone', $user->timezone(), DB::STRING);
        $query->setParameter(':statusId', $user->statusId(), DB::STRING);
        $query->setParameter(':lastLoginAt', $user->lastLoginAt(), DB::DATETIME_MUTABLE);
        $query->setParameter(':createdAt', $user->createdAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedAt', $user->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':createdBy', $user->createdBy(), DB::INTEGER);

        $query->execute();

        return (int)$query->getConnection()->lastInsertId();
    }

    public function get(User $user): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'user.Id as id',
            'user.Email as email',
            'user.Name as name',
            'user.Password as password',
            'user.Timezone as timezone',
            'user.StatusId as statusId',
            'user.LastLoginAt as lastLoginAt',
            'user.CreatedAt as createdAt',
            'user.UpdatedAt as updatedAt',
            'user.CreatedBy as createdBy',
            'user.UpdatedBy as updatedBy',
            'statusId.id as `statusId.id`',
            'statusId.name as `statusId.name`',
            'createdBy.id as `createdBy.id`',
            'createdBy.name as `createdBy.name`',
            'updatedBy.id as `updatedBy.id`',
            'updatedBy.name as `updatedBy.name`',
        ]);
        $query->from('`Users`', '`user`');
        $query->leftJoin('`user`', '`UserStatus`', '`statusId`', 'user.StatusId = statusId.id');
        $query->leftJoin('`user`', '`Users`', '`createdBy`', 'user.CreatedBy = createdBy.id');
        $query->leftJoin('`user`', '`Users`', '`updatedBy`', 'user.UpdatedBy = updatedBy.id');
        $query->where('user.Id = :id');
        $query->setParameter(':id', $user->id(), DB::INTEGER);

        return $query->execute()->fetch() ?: [];
    }

    public function shift(User $user): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->update('`Users`');

        $query->set('Email', ':email');
        $query->set('Name', ':name');
        $query->set('Password', ':password');
        $query->set('Timezone', ':timezone');
        $query->set('StatusId', ':statusId');
        $query->set('LastLoginAt', ':lastLoginAt');
        $query->set('UpdatedAt', ':updatedAt');
        $query->set('UpdatedBy', ':updatedBy');

        $query->setParameter(':email', $user->email(), DB::STRING);
        $query->setParameter(':name', $user->name(), DB::STRING);
        $query->setParameter(':password', $user->password(), DB::STRING);
        $query->setParameter(':timezone', $user->timezone(), DB::STRING);
        $query->setParameter(':statusId', $user->statusId(), DB::STRING);
        $query->setParameter(':lastLoginAt', $user->lastLoginAt(), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedAt', $user->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedBy', $user->updatedBy(), DB::INTEGER);

        $query->where('Id = :id');
        $query->setParameter(':id', $user->id(), DB::INTEGER);

        $query->execute();
    }

    public function pop(User $user): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->delete('`Users`');

        $query->where('Id = :id');
        $query->setParameter(':id', $user->id(), DB::INTEGER);

        $query->execute();
    }

    public function getBy(string $column, $value): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'Id as id',
            'Email as email',
            'Name as name',
            'Password as password',
            'Timezone as timezone',
            'StatusId as statusId',
            'LastLoginAt as lastLoginAt',
            'CreatedAt as createdAt',
            'UpdatedAt as updatedAt',
            'CreatedBy as createdBy',
            'UpdatedBy as updatedBy',
        ]);
        $query->from('`Users`');
        $query->where("{$column} = :column");
        $query->setParameter(':column', $value);

        return $query->execute()->fetch() ?: [];
    }

    public function filterUserStatus(FindUserUserStatusRequest $request, int $page, int $limit): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'userStatus.id as id',
            'userStatus.name as text',
        ]);
        $query->from('`UserStatus`', '`userStatus`');

        $query->where('userStatus.name like :userStatus_name');
        $query->setParameter(':userStatus_name', "%{$request->term}%");

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }
}
