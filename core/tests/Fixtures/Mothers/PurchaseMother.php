<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers;

use App\Domain\Purchase\Purchase;
use App\Tests\Fixtures\Mothers\Base\BaseMother;
use App\Tests\Fixtures\Mothers\Base\MotherMapping;

final class PurchaseMother extends BaseMother
{
    private const array AMAZON_INV_1 = [
        'provider' => [ProviderMother::class, 'amazon'],
        'purchasedAt' => ['today', MotherMapping::DATE_IMMUTABLE],
        'invoice' => [PurchaseInvoiceMother::class, 'invoice1'],
        'createdBy' => 'test',
    ];

    private const array BEST_BUY_INV_2 = [
        'provider' => [ProviderMother::class, 'bestBuy'],
        'purchasedAt' => ['today', MotherMapping::DATE_IMMUTABLE],
        'invoice' => [PurchaseInvoiceMother::class, 'invoice2'],
        'createdBy' => 'test',
    ];

    public static function amazonInv1(...$overrides): Purchase
    {
        return self::create(self::AMAZON_INV_1, $overrides);
    }

    public static function bestBuyInv2(...$overrides): Purchase
    {
        return self::create(self::BEST_BUY_INV_2, $overrides);
    }

    protected static function create(array $values, array $overrides = []): Purchase
    {
        $fields = self::merge($values, $overrides);
        extract($fields);

        return new Purchase(
            $provider,
            $purchasedAt,
            $invoice,
            $createdBy,
        );
    }
}
