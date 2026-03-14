<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers;

use App\Domain\Editorial\Editorial;
use App\Tests\Fixtures\Mothers\Base\BaseMother;

final class EditorialMother extends BaseMother
{
    private const array ANAYA = [
        'name' => 'Anaya',
        'comercialName' => 'Anaya Inc.',
        'contact' => [EnterpriseContactMother::class, 'anaya'],
        'address' => [AddressMother::class, 'anytown'],
        'createdBy' => 'test',
    ];

    public static function anaya(...$overrides): Editorial
    {
        return self::create(self::ANAYA, $overrides);
    }

    protected static function create(array $values, array $overrides = []): Editorial
    {
        $fields = self::merge($values, $overrides);
        extract($fields);

        return new Editorial(
            $name,
            $comercialName,
            $contact,
            $address,
            $createdBy,
        );
    }
}
