<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers;

use App\Domain\Purchase\PurchaseInvoice;
use App\Tests\Fixtures\Mothers\Base\BaseMother;

final class PurchaseInvoiceMother extends BaseMother
{
    private const array INVOICE_1 = [
        'number' => 'INV-001',
        'amount' => 100.0,
        'taxes' => 20.0,
        'total' => 120.0,
    ];

    private const array INVOICE_2 = [
        'number' => 'INV-002',
        'amount' => 135.0,
        'taxes' => 45.0,
        'total' => 180.0,
    ];

    public static function invoice1(...$overrides): PurchaseInvoice
    {
        return self::create(self::INVOICE_1, $overrides);
    }

    public static function invoice2(...$overrides): PurchaseInvoice
    {
        return self::create(self::INVOICE_2, $overrides);
    }

    protected static function create(array $values, array $overrides = []): PurchaseInvoice
    {
        $fields = self::merge($values, $overrides);
        extract($fields);

        return new PurchaseInvoice(
            $number,
            $amount,
            $taxes,
            $total,
        );
    }
}
