<?php declare(strict_types=1);

namespace FlexPHP\Bundle\UserBundle\Domain\UserStatus\Response;

use FlexPHP\Messages\ResponseInterface;

final class IndexUserStatusResponse implements ResponseInterface
{
    public $userStatus;

    public function __construct(array $userStatus)
    {
        $this->userStatus = $userStatus;
    }
}
