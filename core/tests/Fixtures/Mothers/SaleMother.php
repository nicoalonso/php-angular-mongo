<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers;

use App\Domain\Sale\Sale;
use App\Tests\Fixtures\Mothers\Base\BaseMother;

final class SaleMother extends BaseMother
{
    private const array JOHN_DOE_SALE_1 = [
        'customer' => [CustomerMother::class, 'johnDoe'],
        'number' => 'F-00001',
        'invoice' => [SaleInvoiceMother::class, 'johnDoeSale1'],
        'createdBy' => 'test',
    ];

    private const array JOHN_DOE_SALE_2 = [
        'customer' => [CustomerMother::class, 'johnDoe'],
        'number' => 'F-00001',
        'invoice' => [SaleInvoiceMother::class, 'johnDoeSale2'],
        'createdBy' => 'test',
    ];

    public static function johnDoeSale1(...$overrides): Sale
    {
        return self::create(self::JOHN_DOE_SALE_1, $overrides);
    }

    public static function johnDoeSale2(...$overrides): Sale
    {
        return self::create(self::JOHN_DOE_SALE_2, $overrides);
    }

    protected static function create(array $values, array $overrides = []): Sale
    {
        $fields = self::merge($values, $overrides);
        extract($fields);

        return new Sale(
            $customer,
            $number,
            $invoice,
            $createdBy,
        );
    }
}
