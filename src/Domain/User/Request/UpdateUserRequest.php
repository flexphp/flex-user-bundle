<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\UserBundle\Domain\User\Request;

use FlexPHP\Messages\RequestInterface;

final class UpdateUserRequest implements RequestInterface
{
    public $id;

    public $email;

    public $name;

    public $password;

    public $timezone;

    public $statusId;

    public $lastLoginAt;

    public $updatedBy;

    public function __construct(int $id, array $data, int $updatedBy)
    {
        $this->id = $id;
        $this->email = $data['email'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->password = $data['password'] ?? null;
        $this->timezone = $data['timezone'] ?? null;
        $this->statusId = $data['statusId'] ?? null;
        $this->lastLoginAt = $data['lastLoginAt'] ?? null;
        $this->updatedBy = $updatedBy;
    }
}
