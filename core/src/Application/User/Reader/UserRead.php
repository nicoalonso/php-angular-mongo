<?php declare(strict_types=1);

namespace App\Application\User\Reader;

use App\Domain\User\User;
use App\Domain\User\UserRepository;

final readonly class UserRead
{
    public function __construct(private UserRepository $repoUser) {}

    public function dispatch(): User
    {
        return $this->repoUser->obtainUser();
    }
}
