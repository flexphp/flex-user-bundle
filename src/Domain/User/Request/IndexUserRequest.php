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

use FlexPHP\Bundle\HelperBundle\Domain\Helper\DateTimeTrait;
use FlexPHP\Messages\RequestInterface;

final class IndexUserRequest implements RequestInterface
{
    use DateTimeTrait;

    public $id;

    public $email;

    public $name;

    public $password;

    public $timezone;

    public $statusId;

    public $lastLoginAt;

    public $createdAt = [];

    public $updatedAt;

    public $createdBy;

    public $updatedBy;

    public $_page;

    public $_limit;

    public $_offset;

    public function __construct(array $data, int $_page, int $_limit = 50, ?string $timezone = null)
    {
        $this->id = $data['id'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->password = $data['password'] ?? null;
        $this->timezone = $data['timezone'] ?? null;
        $this->statusId = $data['statusId'] ?? null;
        $this->lastLoginAt = $data['lastLoginAt'] ?? null;
        $this->createdAt[] = $data['createdAt_START'] ?? null;
        $this->createdAt[] = $data['createdAt_END'] ?? null;
        $this->updatedAt = $data['updatedAt'] ?? null;
        $this->createdBy = $data['createdBy'] ?? null;
        $this->updatedBy = $data['updatedBy'] ?? null;
        $this->_page = $_page;
        $this->_limit = $_limit;
        $this->_offset = $this->getOffset($this->getTimezone($timezone));
    }
}
