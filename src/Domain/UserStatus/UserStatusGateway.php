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

interface UserStatusGateway
{
    public function search(array $wheres, array $orders, int $page, int $limit, int $offset): array;

    public function push(UserStatus $userStatus): string;

    public function get(UserStatus $userStatus): array;

    public function shift(UserStatus $userStatus): void;

    public function pop(UserStatus $userStatus): void;
}
