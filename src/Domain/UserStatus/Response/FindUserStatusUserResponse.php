<?php declare(strict_types=1);

namespace FlexPHP\Bundle\UserBundle\Domain\UserStatus\Response;

use FlexPHP\Messages\ResponseInterface;

final class FindUserStatusUserResponse implements ResponseInterface
{
    public $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }
}
