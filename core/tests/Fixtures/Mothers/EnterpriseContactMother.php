<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers;

use App\Domain\Common\EnterpriseContact;
use App\Tests\Fixtures\Mothers\Base\BaseMother;

final class EnterpriseContactMother extends BaseMother
{
    private const array AMAZON = [
        'email' => 'info@amazon.com',
        'website' => 'https://www.amazon.com',
        'phone1' => '+1-800-123-4567',
        'phone2' => '+1-800-987-6543',
    ];

    private const array BEST_BUY = [
        'email' => 'info@bestbuy.com',
        'website' => 'https://www.bestbuy.com',
        'phone1' => '+1-800-123-4567',
        'phone2' => '+1-800-987-6543',
    ];

    private const array ANAYA = [
        'email' => 'info@anaya.com',
        'website' => 'https://www.anaya.com',
        'phone1' => '+34-900-123-456',
        'phone2' => '+34-900-987-654',
    ];

    public static function amazon(...$overrides): EnterpriseContact
    {
        return self::create(self::AMAZON, $overrides);
    }

    public static function bestBuy(...$overrides): EnterpriseContact
    {
        return self::create(self::BEST_BUY, $overrides);
    }

    public static function anaya(...$overrides): EnterpriseContact
    {
        return self::create(self::ANAYA, $overrides);
    }

    protected static function create(array $values, array $overrides = []): EnterpriseContact
    {
        $fields = self::merge($values, $overrides);
        extract($fields);

        return new EnterpriseContact(
            $email,
            $website,
            $phone1,
            $phone2
        );
    }
}