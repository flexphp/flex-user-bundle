<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\UserBundle\Domain\UserStatus\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use FlexPHP\Bundle\HelperBundle\Domain\Helper\DbalCriteriaHelper;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\UserStatus;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\UserStatusGateway;

class MySQLUserStatusGateway implements UserStatusGateway
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
            'userStatus.Id as id',
            'userStatus.Name as name',
        ]);
        $query->from('`UserStatus`', '`userStatus`');

        $query->orderBy('userStatus.UpdatedAt', 'DESC');

        $criteria = new DbalCriteriaHelper($query, $offset);

        foreach ($wheres as $column => $value) {
            $criteria->getCriteria('userStatus', $column, $value, $this->operator[$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }

    public function push(UserStatus $userStatus): string
    {
        $query = $this->conn->createQueryBuilder();

        $query->insert('`UserStatus`');

        $query->setValue('Id', ':id');
        $query->setValue('Name', ':name');
        $query->setValue('CreatedAt', ':createdAt');
        $query->setValue('UpdatedAt', ':updatedAt');
        $query->setValue('CreatedBy', ':createdBy');

        $query->setParameter(':id', $userStatus->id(), DB::STRING);
        $query->setParameter(':name', $userStatus->name(), DB::STRING);
        $query->setParameter(':createdAt', $userStatus->createdAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedAt', $userStatus->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':createdBy', $userStatus->createdBy(), DB::INTEGER);

        $query->execute();

        return $userStatus->id();
    }

    public function get(UserStatus $userStatus): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'userStatus.Id as id',
            'userStatus.Name as name',
            'userStatus.CreatedAt as createdAt',
            'userStatus.UpdatedAt as updatedAt',
            'userStatus.CreatedBy as createdBy',
            'userStatus.UpdatedBy as updatedBy',
            'createdBy.id as `createdBy.id`',
            'createdBy.name as `createdBy.name`',
            'updatedBy.id as `updatedBy.id`',
            'updatedBy.name as `updatedBy.name`',
        ]);
        $query->from('`UserStatus`', '`userStatus`');
        $query->leftJoin('`userStatus`', '`Users`', '`createdBy`', 'userStatus.CreatedBy = createdBy.id');
        $query->leftJoin('`userStatus`', '`Users`', '`updatedBy`', 'userStatus.UpdatedBy = updatedBy.id');
        $query->where('userStatus.Id = :id');
        $query->setParameter(':id', $userStatus->id(), DB::STRING);

        return $query->execute()->fetch() ?: [];
    }

    public function shift(UserStatus $userStatus): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->update('`UserStatus`');

        $query->set('Id', ':id');
        $query->set('Name', ':name');
        $query->set('UpdatedAt', ':updatedAt');
        $query->set('UpdatedBy', ':updatedBy');

        $query->setParameter(':id', $userStatus->id(), DB::STRING);
        $query->setParameter(':name', $userStatus->name(), DB::STRING);
        $query->setParameter(':updatedAt', $userStatus->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedBy', $userStatus->updatedBy(), DB::INTEGER);

        $query->where('Id = :id');
        $query->setParameter(':id', $userStatus->id(), DB::STRING);

        $query->execute();
    }

    public function pop(UserStatus $userStatus): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->delete('`UserStatus`');

        $query->where('Id = :id');
        $query->setParameter(':id', $userStatus->id(), DB::STRING);

        $query->execute();
    }
}
