<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers;

use App\Domain\Book\BookSale;
use App\Tests\Fixtures\Mothers\Base\BaseMother;

final class BookSaleMother extends BaseMother
{
    private const array VALID = [
        'saleable' => true,
        'price' => 100.0,
        'discount' => 10.0,
    ];

    public static function valid(...$overrides): BookSale
    {
        return self::create(self::VALID, $overrides);
    }

    protected static function create(array $values, array $overrides = []): BookSale
    {
        $fields = self::merge($values, $overrides);
        extract($fields);

        return new BookSale(
            $saleable,
            $price,
            $discount,
        );
    }
}
