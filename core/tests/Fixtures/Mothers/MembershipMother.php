<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers;

use App\Domain\Customer\Membership;
use App\Tests\Fixtures\Mothers\Base\BaseMother;

final class MembershipMother extends BaseMother
{
    private const array ACTIVE = [
        'number' => 'SN00025',
    ];

    public static function active(...$overrides): Membership
    {
        return self::create(self::ACTIVE, $overrides);
    }

    protected static function create(array $values, array $overrides = []): Membership
    {
        $fields = self::merge($values, $overrides);
        extract($fields);

        return new Membership($number);
    }
}
