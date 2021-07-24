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

use FlexPHP\Bundle\HelperBundle\Domain\Helper\ToArrayTrait;
use FlexPHP\Bundle\UserBundle\Domain\UserStatus\UserStatus;
use Symfony\Component\Security\Core\User\UserInterface;

final class User implements UserInterface
{
    use ToArrayTrait;

    private $id;

    private $email;

    private $name;

    private $password;

    private $timezone;

    private $statusId;

    private $lastLoginAt;

    private $createdAt;

    private $updatedAt;

    private $createdBy;

    private $updatedBy;

    private $statusIdInstance;

    private $createdByInstance;

    private $updatedByInstance;

    public function id(): ?int
    {
        return $this->id;
    }

    public function email(): ?string
    {
        return $this->email;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function password(): ?string
    {
        return $this->password;
    }

    public function timezone(): ?string
    {
        return $this->timezone;
    }

    public function statusId(): ?string
    {
        return $this->statusId;
    }

    public function lastLoginAt(): ?\DateTime
    {
        return $this->lastLoginAt;
    }

    public function createdAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function createdBy(): ?int
    {
        return $this->createdBy;
    }

    public function updatedBy(): ?int
    {
        return $this->updatedBy;
    }

    public function statusIdInstance(): ?UserStatus
    {
        return $this->statusIdInstance;
    }

    public function createdByInstance(): ?self
    {
        return $this->createdByInstance;
    }

    public function updatedByInstance(): ?self
    {
        return $this->updatedByInstance;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setTimezone(string $timezone): void
    {
        $this->timezone = $timezone;
    }

    public function setStatusId(?string $statusId): void
    {
        $this->statusId = $statusId;
    }

    public function setLastLoginAt(?\DateTime $lastLoginAt): void
    {
        $this->lastLoginAt = $lastLoginAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setCreatedBy(?int $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function setUpdatedBy(?int $updatedBy): void
    {
        $this->updatedBy = $updatedBy;
    }

    public function setStatusIdInstance(?UserStatus $userStatus): void
    {
        $this->statusIdInstance = $userStatus;
    }

    public function setCreatedByInstance(?self $user): void
    {
        $this->createdByInstance = $user;
    }

    public function setUpdatedByInstance(?self $user): void
    {
        $this->updatedByInstance = $user;
    }

    public function getUsername()
    {
        return $this->name();
    }

    public function getPassword()
    {
        return $this->password();
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return (new UserRbac())->getRoles($this->email());
    }

    public function eraseCredentials()
    {
        return true;
    }
}
