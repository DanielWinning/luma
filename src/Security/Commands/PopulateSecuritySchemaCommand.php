<?php

namespace App\Security\Commands;

use App\Security\Entity\Permission;
use App\Security\Entity\Role;
use App\Security\Entity\User;
use Luma\AuroraDatabase\Utils\Collection;
use Luma\Framework\Classes\Helper\DatabaseConnectionHelper;
use Luma\SecurityComponent\Authentication\Password;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'luma:security:populate', description: 'Populates initial user and security tables')]
class PopulateSecuritySchemaCommand extends Command
{
    private SymfonyStyle $style;

    /**
     * @return void
     *
     * @throws \Exception
     */
    protected function configure(): void
    {
        DatabaseConnectionHelper::connect();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->style = new SymfonyStyle($input, $output);

        $this->style->title('Executing Populate Security Schema');

        $this->createPermissions();

        $this->createRoles();

        $this->createAdminUser();

        return Command::SUCCESS;
    }

    /**
     * @return void
     *
     * @throws \Exception
     */
    private function createPermissions(): void
    {
        $this->style->section('Creating Permissions');

        if (!file_exists($this->getDataPath())) {
            $this->style->info('Skipping permission creation due to missing security.json file');
            return;
        }

        $permissionsData = json_decode(file_get_contents($this->getDataPath()))->permissions;
        $created = 0;

        foreach ($permissionsData as $permission) {
            $existingPermission = Permission::select()->whereIs('handle', $permission->handle)->get();

            if ($existingPermission) {
                $this->style->text(sprintf('Skipping existing permission %s', $permission->handle));
                continue;
            }

            Permission::create([
                'name' => $permission->name,
                'handle' => $permission->handle,
            ])->save();

            $created++;
            $this->style->text(sprintf('Added new permission: %s', $permission->handle));
        }

        if ($created) {
            $this->style->success('Updated Security.ublPermission');
        } else {
            $this->style->info('All records skipped, no changes made to Security.ublPermission');
        }
    }

    /**
     * @return void
     *
     * @throws \ReflectionException
     */
    private function createRoles(): void
    {
        $this->style->section('Creating Roles');

        if (!file_exists($this->getDataPath())) {
            $this->style->info('Skipping role creation due to missing security.json file');
            return;
        }

        $rolesData = json_decode(file_get_contents($this->getDataPath()))->roles;
        $created = 0;

        foreach ($rolesData as $role) {
            $existingRole = Role::select()->whereIs('handle', $role->handle)->get();

            if ($existingRole) {
                $this->style->text(sprintf('Skipping existing role %s', $role->handle));
                continue;
            }

            $permissions = new Collection();

            foreach ($role->permissions as $permissionHandle) {
                $permission = Permission::select()->whereIs('handle', $permissionHandle)->get();

                if (!$permission) continue;

                $permissions->add($permission);
            }

            Role::create([
                'name' => $role->name,
                'handle' => $role->handle,
                'permissions' => $permissions,
            ])->save();

            $created++;
            $this->style->text(sprintf('Added new role: %s', $role->handle));
        }

        if ($created) {
            $this->style->success('Updated Security.ublRole');
        } else {
            $this->style->info('All records skipped, no changes made to Security.ublRole');
        }
    }

    /**
     * @return void
     *
     * @throws \ReflectionException
     */
    private function createAdminUser(): void
    {
        $this->style->section('Creating Admin User');

        if (!isset($_ENV['ADMIN_EMAIL']) || !isset($_ENV['ADMIN_PASSWORD']) || !isset($_ENV['ADMIN_ROLES'])) {
            $this->style->info('Skipping creating admin user due to missing environment variables');
        }

        $existingUser = User::select()->whereIs('emailAddress', $_ENV['ADMIN_EMAIL'])->get();

        if ($existingUser) {
            $this->style->text('Skipping admin user creation as user already exists');

            $this->style->info('Skipped admin user creation');
        } else {
            $adminRoles = explode(',', $_ENV['ADMIN_ROLES']);

            $roles = new Collection();

            foreach ($adminRoles as $roleHandle) {
                $existingRole = Role::select()->whereIs('handle', $roleHandle)->get();

                if ($existingRole) {
                    $roles->add($existingRole);
                }
            }

            if ($roles->isEmpty()) {
                $this->style->info('Will not create admin user without valid roles');
                return;
            }

            User::create([
                'password' => Password::hash($_ENV['ADMIN_PASSWORD']),
                'emailAddress' => $_ENV['ADMIN_EMAIL'],
                'roles' => $roles,
            ])->save();

            $this->style->success('Admin user created');
        }
    }

    /**
     * @return string
     */
    private function getDataPath(): string
    {
        return sprintf('%s/data/security.json', dirname(__DIR__, 3));
    }
}