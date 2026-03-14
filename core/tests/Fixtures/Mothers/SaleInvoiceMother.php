<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers;

use App\Domain\Sale\SaleInvoice;
use App\Tests\Fixtures\Mothers\Base\BaseMother;
use App\Tests\Fixtures\Mothers\Base\MotherMapping;

final class SaleInvoiceMother extends BaseMother
{
    private const array JOHN_DOE_SALE_1 = [
        'date' => ['2024-01-01', MotherMapping::DATE_IMMUTABLE],
        'amount' => 100,
        'taxPercentage' => 21,
        'taxes' => 21,
        'total' => 121,
    ];

    private const array JOHN_DOE_SALE_2 = [
        'date' => ['2026-03-06', MotherMapping::DATE_IMMUTABLE],
        'amount' => 80,
        'taxPercentage' => 21,
        'taxes' => 16.8,
        'total' => 96.8,
    ];

    public static function johnDoeSale1(...$overrides): SaleInvoice
    {
        return self::create(self::JOHN_DOE_SALE_1, $overrides);
    }

    public static function johnDoeSale2(...$overrides): SaleInvoice
    {
        return self::create(self::JOHN_DOE_SALE_2, $overrides);
    }

    protected static function create(array $values, array $overrides = []): SaleInvoice
    {
        $fields = self::merge($values, $overrides);
        extract($fields);

        return new SaleInvoice(
            $date,
            $amount,
            $taxPercentage,
            $taxes,
            $total,
        );
    }
}
