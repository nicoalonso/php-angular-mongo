<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers;

use App\Domain\Borrow\Borrow;
use App\Tests\Fixtures\Mothers\Base\BaseMother;

final class BorrowMother extends BaseMother
{
    private const array JOHN_DOE = [
        'customer' => [CustomerMother::class, 'johnDoe'],
        'number' => 'P-00022',
        'totalBooks' => 3,
        'createdBy' => 'test',
    ];

    public static function johnDoe(...$overrides): Borrow
    {
        return self::create(self::JOHN_DOE, $overrides);
    }

    protected static function create(array $values, array $overrides = []): Borrow
    {
        $fields = self::merge($values, $overrides);
        extract($fields);

        return new Borrow(
            $customer,
            $number,
            $totalBooks,
            $createdBy
        );
    }
}
