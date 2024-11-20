<?php

namespace Tests\Entity;

use App\Entity\User;
use Luma\AuroraDatabase\Model\Aurora;
use Luma\AuroraDatabase\Utils\Collection;
use Luma\SecurityComponent\Authentication\Password;
use Luma\SecurityComponent\Entity\Role;
use Tests\AbstractLumaTestCase;

class UserTest extends AbstractLumaTestCase
{
    /**
     * @return void
     */
    public function testItInstantiates(): void
    {
        self::assertInstanceOf(Aurora::class, new User());
    }

    /**
     * @return void
     */
    public function testGetters(): void
    {
        $password = Password::hash('password123');
        $role = Role::create([
            'name' => 'Test Role',
            'handle' => 'test_role',
        ]);
        $user = User::create([
            'username' => 'Test',
            'password' => $password,
            'emailAddress' => 'test@test.com',
            'roles' => new Collection([$role]),
        ]);

        self::assertEquals('Test', $user->getUsername());
        self::assertEquals('test@test.com', $user->getEmailAddress());
        self::assertEquals($password, $user->getPassword());
        self::assertCount(1, $user->getRoles());
        self::assertEquals($role->getName(), $user->getRoles()->first()->getName());
    }
}