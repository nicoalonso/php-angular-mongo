<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers;

use App\Domain\Customer\Customer;
use App\Tests\Fixtures\Mothers\Base\BaseMother;

final class CustomerMother extends BaseMother
{
    private const array JOHN_DOE = [
        'name' => 'John',
        'surname' => 'Doe',
        'membership' => [MembershipMother::class, 'active'],
        'contact' => [ContactInfoMother::class, 'doe'],
        'address' => [AddressMother::class, 'anytown'],
        'vatNumber' => '12345667A',
        'createdBy' => 'test',
    ];

    public static function johnDoe(...$overrides): Customer
    {
        return self::create(self::JOHN_DOE, $overrides);
    }

    protected static function create(array $values, array $overrides = []): Customer
    {
        $fields = self::merge($values, $overrides);
        extract($fields);

        return new Customer(
            $name,
            $surname,
            $membership,
            $contact,
            $address,
            $vatNumber,
            $createdBy
        );
    }
}
