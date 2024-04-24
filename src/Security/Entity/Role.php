<?php

namespace App\Security\Entity;

use Luma\AuroraDatabase\Attributes\AuroraCollection;
use Luma\AuroraDatabase\Attributes\Column;
use Luma\AuroraDatabase\Attributes\Identifier;
use Luma\AuroraDatabase\Attributes\Schema;
use Luma\AuroraDatabase\Attributes\Table;
use Luma\AuroraDatabase\Utils\Collection;
use Luma\SecurityComponent\Authorization\AbstractRole;

#[Schema('Security')]
#[Table('ublRole')]
class Role extends AbstractRole
{
    #[Identifier]
    #[Column('intRoleId')]
    protected int $id;

    #[Column('strName')]
    protected string $name;

    #[Column('strHandle')]
    protected string $handle;

    #[AuroraCollection(
        class: Permission::class,
        pivotSchema: 'Security',
        pivotTable: 'tblPermissionRole',
        pivotColumn: 'intPermissionId'
    )]
    protected Collection $permissions;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHandle(): string
    {
        return $this->handle;
    }

    /**
     * @return Collection
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }
}