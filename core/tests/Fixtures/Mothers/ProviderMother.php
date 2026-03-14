<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers;

use App\Domain\Provider\Provider;
use App\Tests\Fixtures\Mothers\Base\BaseMother;

final class ProviderMother extends BaseMother
{
    private const array AMAZON = [
        'name' => 'Amazon',
        'comercialName' => 'Amazon, Inc.',
        'contact' => [EnterpriseContactMother::class, 'amazon'],
        'address' => [AddressMother::class, 'anytown'],
        'vatNumber' => 'B36565656',
        'createdBy' => 'test',
    ];

    private const array BEST_BUY = [
        'name' => 'Best Buy',
        'comercialName' => 'Best Buy Co., Inc.',
        'contact' => [EnterpriseContactMother::class, 'bestBuy'],
        'address' => [AddressMother::class, 'anytown'],
        'vatNumber' => 'B36565656',
        'createdBy' => 'test',
    ];

    public static function amazon(...$overrides): Provider
    {
        return self::create(self::AMAZON, $overrides);
    }

    public static function bestBuy(...$overrides): Provider
    {
        return self::create(self::BEST_BUY, $overrides);
    }

    protected static function create(array $values, array $overrides = []): Provider
    {
        $fields = self::merge($values, $overrides);
        extract($fields);

        return new Provider(
            $name,
            $comercialName,
            $contact,
            $address,
            $vatNumber,
            $createdBy,
        );
    }
}
