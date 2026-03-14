<?php declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Domain\User\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @codeCoverageIgnore
 */
final readonly class UserProvider implements UserProviderInterface
{
    private const string USERNAME_FIELD = 'username';
    private const string DISPLAY_NAME_FIELD = 'displayName';
    private const string ROLES_FIELD = 'roles';

    public function __construct(private RequestStack $requestStack) {}

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $session = $this->requestStack->getSession();
        $username = $session->get(self::USERNAME_FIELD);
        if (empty($username)) {
            throw new AuthenticationException('Invalid credentials');
        }

        $displayName = $session->get(self::DISPLAY_NAME_FIELD);
        $roles = $session->get(self::ROLES_FIELD);

        return new User($username, $displayName, $roles);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }
}
