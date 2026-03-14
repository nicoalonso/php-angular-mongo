<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers;

use App\Domain\Common\Address;
use App\Tests\Fixtures\Mothers\Base\BaseMother;

final class AddressMother extends BaseMother
{
    private const array ANYTOWN = [
        'street' => '123 Main Street',
        'postalCode' => '12345',
        'city' => 'Anytown',
        'province' => 'Alaska',
        'country' => 'EEUU',
    ];

    public static function anytown(...$overrides): Address
    {
        return self::create(self::ANYTOWN, $overrides);
    }

    protected static function create(array $values, array $overrides = []): Address
    {
        $fields = self::merge($values, $overrides);
        extract($fields);

        return new Address(
            $street,
            $postalCode,
            $city,
            $province,
            $country,
        );
    }
}
