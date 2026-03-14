<?php

namespace App\Tests\Domain\User;

use App\Domain\User\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $user = new User('jdoe@gmail.com', 'John Doe', ['user']);

        self::assertEquals('jdoe@gmail.com', $user->getName());
        self::assertEquals('John Doe', $user->getDisplayName());
        self::assertEquals(['user'], $user->getGroups());
        self::assertEquals(['ROLE_USER'], $user->getRoles());
        self::assertEquals('jdoe@gmail.com', $user->getUserIdentifier());
        self::assertFalse($user->isAdmin());
    }

    public function testShouldRunWhenAsAdmin(): void
    {
        $user = new User('jdoe@gmail.com', 'John Doe', ['admin']);
        $user->eraseCredentials();

        self::assertEquals('jdoe@gmail.com', $user->getName());
        self::assertEquals('John Doe', $user->getDisplayName());
        self::assertEquals(['admin'], $user->getGroups());
        self::assertEquals(['ROLE_ADMIN'], $user->getRoles());
        self::assertEquals('jdoe@gmail.com', $user->getUserIdentifier());
        self::assertTrue($user->isAdmin());
    }
}
