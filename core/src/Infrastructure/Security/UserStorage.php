<?php declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Domain\User\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * @codeCoverageIgnore
 */
final readonly class UserStorage
{
    public function __construct(private TokenStorageInterface $tokenStorage) {}

    public function load(): User
    {
        /**
         * @var User $user
         */
        $user = $this->tokenStorage->getToken()?->getUser();
        if (null === $user) {
            throw new AuthenticationException('User not found');
        }

        return $user;
    }
}
