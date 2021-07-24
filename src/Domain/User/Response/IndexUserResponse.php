<?php declare(strict_types=1);

namespace FlexPHP\Bundle\UserBundle\Domain\User\Response;

use FlexPHP\Messages\ResponseInterface;

final class IndexUserResponse implements ResponseInterface
{
    public $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }
}
