<?php declare(strict_types=1);

namespace FlexPHP\Bundle\UserBundle\Domain\User\Response;

use FlexPHP\Bundle\UserBundle\Domain\User\User;
use FlexPHP\Messages\ResponseInterface;

final class UpdateUserResponse implements ResponseInterface
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
