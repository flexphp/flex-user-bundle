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

use FlexPHP\Bundle\UserBundle\Domain\User\Request\FindUserUserStatusRequest;

interface UserGateway
{
    public function search(array $wheres, array $orders, int $page, int $limit, int $offset): array;

    public function push(User $user): int;

    public function get(User $user): array;

    public function shift(User $user): void;

    public function pop(User $user): void;

    public function getBy(string $column, $value): array;

    public function filterUserStatus(FindUserUserStatusRequest $request, int $page, int $limit): array;
}
