<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Session;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use App\Infrastructure\Security\UserStorage;

/**
 * @codeCoverageIgnore
 */
final readonly class SessionUserRepository implements UserRepository
{
    public function __construct(private UserStorage $storage) {}

    public function obtainUser(): User
    {
        return $this->storage->load();
    }
}
