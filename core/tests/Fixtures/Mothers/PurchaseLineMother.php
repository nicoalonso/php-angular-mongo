<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers;

use App\Domain\Purchase\PurchaseLine;
use App\Tests\Fixtures\Mothers\Base\BaseMother;

final class PurchaseLineMother extends BaseMother
{
    private const array AMAZON_LINE_1 = [
        'purchase' => [PurchaseMother::class, 'amazonInv1'],
        'book' => [BookMother::class, 'romeoAndJuliet'],
        'quantity' => 2,
        'unitPrice' => 10.0,
        'discountPercentage' => 5.0,
        'total' => 19.0,
    ];

    private const array AMAZON_LINE_2 = [
        'purchase' => [PurchaseMother::class, 'amazonInv1'],
        'book' => [BookMother::class, 'donQuijote'],
        'quantity' => 3,
        'unitPrice' => 15.0,
        'discountPercentage' => 10.0,
        'total' => 40.5,
    ];

    private const array BEST_BUY_LINE_1 = [
        'purchase' => [PurchaseMother::class, 'bestBuyInv2'],
        'book' => [BookMother::class, 'romeoAndJuliet'],
        'quantity' => 1,
        'unitPrice' => 20.0,
        'discountPercentage' => 0.0,
        'total' => 20.0,
    ];

     public static function amazonLine1(...$overrides): PurchaseLine
     {
         return self::create(self::AMAZON_LINE_1, $overrides);
     }

     public static function amazonLine2(...$overrides): PurchaseLine
     {
         return self::create(self::AMAZON_LINE_2, $overrides);
     }

     public static function bestBuyLine1(...$overrides): PurchaseLine
     {
         return self::create(self::BEST_BUY_LINE_1, $overrides);
     }

    protected static function create(array $values, array $overrides = []): PurchaseLine
    {
        $fields = self::merge($values, $overrides);
        extract($fields);

        return new PurchaseLine(
            $purchase,
            $book,
            $quantity,
            $unitPrice,
            $discountPercentage,
            $total,
        );
    }
}
