<?php declare(strict_types=1);

namespace App\Domain\User;

use Symfony\Component\Security\Core\User\UserInterface;

final class User implements UserInterface
{
    private const string ADMIN_GROUP = 'admin';
    private const string ROLE_PREFIX = 'ROLE_';

    /** @var string[] */
    protected array $roles;

    /**
     * @param string[] $groups
     */
    public function __construct(
        protected readonly string $name,
        protected readonly string $displayName,
        protected readonly array  $groups,
    ) {
        $this->handleGroups($groups);
    }

    protected function handleGroups(array $groupList): void
    {
        $roles = array_map(fn(string $group) => self::ROLE_PREFIX . strtoupper($group), $groupList);
        $this->roles = array_unique($roles);
    }

    public function isAdmin(): bool
    {
        return $this->hasGroup(self::ADMIN_GROUP);
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
        // Nothing to do here
    }

    public function getUserIdentifier(): string
    {
        return $this->name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    public function hasGroup(string $group): bool
    {
        return in_array($group, $this->groups);
    }

    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }
}
