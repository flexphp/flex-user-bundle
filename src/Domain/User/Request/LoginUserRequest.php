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

final class LoginUserRequest implements RequestInterface
{
    public $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }
}
