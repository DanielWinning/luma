<?php

namespace App\Security\Entity;

use Luma\AuroraDatabase\Attributes\AuroraCollection;
use Luma\AuroraDatabase\Attributes\Column;
use Luma\AuroraDatabase\Attributes\Identifier;
use Luma\AuroraDatabase\Attributes\Schema;
use Luma\AuroraDatabase\Attributes\Table;
use Luma\AuroraDatabase\Model\Aurora;
use Luma\AuroraDatabase\Utils\Collection;
use Luma\SecurityComponent\Attributes\SecurityIdentifier;
use Luma\SecurityComponent\Authentication\AbstractUser;

#[Schema('Security')]
#[Table('tblUser')]
class User extends AbstractUser
{
    #[Identifier]
    #[Column('intUserId')]
    protected int $id;

    #[Column('strUsername')]
    protected string $username;

    #[Column('strPassword')]
    protected string $password;

    #[SecurityIdentifier]
    #[Column('strEmailAddress')]
    protected string $emailAddress;

    #[AuroraCollection(
        class: Role::class,
        pivotSchema: 'Security',
        pivotTable: 'tblRoleUser',
        pivotColumn: 'intRoleId'
    )]
    protected Collection $roles;

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return Collection
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    /**
     * @return void
     */
    public static function refresh(): void
    {
        if (isset($_SESSION['user']) && $_SESSION['user'] instanceof Aurora) {
            $user = self::find($_SESSION['user']->getId());
            $_SESSION['user'] = $user->with([Role::class]);
        }
    }
}