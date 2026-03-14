<?php declare(strict_types=1);

namespace App\Tests\Doubles\Infrastructure\Persistence;

use App\Domain\User\User;
use App\Domain\User\UserRepository;

final class UserRepositoryStub implements UserRepository
{
    public User $user;

    public function __construct()
    {
        $this->changeUser();
    }

    public function normalUser(): void
    {
        $this->changeUser(groups: ['user']);
    }

    public function changeUser(
        string $name = 'jdoe@gmail.com',
        string $displayName = 'John Doe',
        array  $groups = ['admin'],
    ): void
    {
        $this->user = new User($name, $displayName, $groups);
    }

    public function obtainUser(): User
    {
        return $this->user;
    }
}
