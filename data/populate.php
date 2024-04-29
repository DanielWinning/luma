<?php

use App\Security\Entity\Permission;
use App\Security\Entity\Role;
use App\Security\Entity\User;
use Luma\AuroraDatabase\Utils\Collection;
use Luma\AuroraDatabase\Utils\Populator;
use Luma\SecurityComponent\Authentication\Password;

require_once dirname(__DIR__) . '/vendor/autoload.php';

try {
    $populator = new Populator();

    $permissions = new Collection([]);
    $populator->populate(Permission::class, $permissions);

    $roles = new Collection([
        [
            'name' => 'Administrator',
            'handle' => 'administrator',
            'permissions' => new Collection([]),
        ],
        [
            'name' => 'User',
            'handle' => 'user',
            'permissions' => new Collection([]),
        ],
    ]);
    $populator->populate(Role::class, $roles);

    $users = new Collection([
        [
            'username' => 'Admin',
            'password' => Password::hash('admin'),
            'emailAddress' => 'admin@lumaphp.com',
            'roles' => new Collection([
                Role::select()->whereIs('handle', 'administrator')->get(),
                Role::select()->whereIs('handle', 'user')->get(),
            ]),
        ],
    ]);
    $populator->populate(User::class, $users);
} catch (\Exception $exception) {
    die(sprintf('Something went wrong: %s', $exception->getMessage()));
}