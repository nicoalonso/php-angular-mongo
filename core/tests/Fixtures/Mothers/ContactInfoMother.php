<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers;

use App\Domain\Customer\ContactInfo;
use App\Tests\Fixtures\Mothers\Base\BaseMother;

final class ContactInfoMother extends BaseMother
{
    private const array DOE_CONTACT_INFO = [
        'email' => 'johndoe@gmail.com',
        'phone1' => '+1234567890',
        'phone2' => '+0987654321',
    ];

    public static function doe(...$overrides): ContactInfo
    {
        return self::create(self::DOE_CONTACT_INFO, $overrides);
    }

    protected static function create(array $values, array $overrides = []): ContactInfo
    {
        $fields = self::merge($values, $overrides);
        extract($fields);

        return new ContactInfo(
            $email,
            $phone1,
            $phone2,
        );
    }
}
