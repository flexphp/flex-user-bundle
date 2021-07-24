<?php declare(strict_types=1);

namespace FlexPHP\Bundle\UserBundle\Domain\UserStatus\Response;

use FlexPHP\Bundle\UserBundle\Domain\UserStatus\UserStatus;
use FlexPHP\Messages\ResponseInterface;

final class ReadUserStatusResponse implements ResponseInterface
{
    public $userStatus;

    public function __construct(UserStatus $userStatus)
    {
        $this->userStatus = $userStatus;
    }
}
