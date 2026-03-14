<?php declare(strict_types=1);

namespace App\Presentation\V1\User;

use App\Domain\User\User;
use App\Presentation\Identity\Result;

final class UserView extends Result
{
    private const string NAME_KEY = 'name';
    private const string DISPLAY_NAME_KEY = 'displayName';
    private const string GROUPS_KEY = 'groups';

    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * @param User $data
     */
    public static function serialize(mixed $data): array
    {
        return [
            self::NAME_KEY => $data->getName(),
            self::DISPLAY_NAME_KEY => $data->getDisplayName(),
            self::GROUPS_KEY => $data->getGroups(),
        ];
    }
}
