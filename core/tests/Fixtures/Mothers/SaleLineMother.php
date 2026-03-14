<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers;

use App\Domain\Sale\SaleLine;
use App\Tests\Fixtures\Mothers\Base\BaseMother;

final class SaleLineMother extends BaseMother
{
    private const array JOHN_SALE_1_LINE_1 = [
        'sale' => [SaleMother::class, 'johnDoeSale1'],
        'book' => [BookMother::class, 'donQuijote'],
        'quantity' => 2,
        'price' => 10.0,
        'discount' => 0.0,
        'total' => 20.0,
    ];

    private const array JOHN_SALE_1_LINE_2 = [
        'sale' => [SaleMother::class, 'johnDoeSale1'],
        'book' => [BookMother::class, 'romeoAndJuliet'],
        'quantity' => 1,
        'price' => 12.0,
        'discount' => 0.0,
        'total' => 12.0,
    ];

    private const array JOHN_SALE_2_LINE_1 = [
        'sale' => [SaleMother::class, 'johnDoeSale1'],
        'book' => [BookMother::class, 'romeoAndJuliet'],
        'quantity' => 3,
        'price' => 11.0,
        'discount' => 5.0,
        'total' => 31.35,
    ];

    public static function johnSale1Line1(...$overrides): SaleLine
    {
        return self::create(self::JOHN_SALE_1_LINE_1, $overrides);
    }

    public static function johnSale1Line2(...$overrides): SaleLine
    {
        return self::create(self::JOHN_SALE_1_LINE_2, $overrides);
    }

    public static function johnSale2Line1(...$overrides): SaleLine
    {
        return self::create(self::JOHN_SALE_2_LINE_1, $overrides);
    }

    protected static function create(array $values, array $overrides = []): SaleLine
    {
        $fields = self::merge($values, $overrides);
        extract($fields);

        return new SaleLine(
            $sale,
            $book,
            $quantity,
            $price,
            $discount,
            $total,
        );
    }
}
