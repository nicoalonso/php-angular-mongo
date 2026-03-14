<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers;

use App\Domain\Borrow\BorrowLine;
use App\Tests\Fixtures\Mothers\Base\BaseMother;

final class BorrowLineMother extends BaseMother
{
    private const array ROMEO_AND_JULIET = [
        'borrow' => [BorrowMother::class, 'johnDoe'],
        'book' => [BookMother::class, 'romeoAndJuliet'],
    ];

    private const array DON_QUIJOTE = [
        'borrow' => [BorrowMother::class, 'johnDoe'],
        'book' => [BookMother::class, 'donQuijote'],
    ];

    public static function romeoAndJuliet(...$overrides): BorrowLine
    {
        return self::create(self::ROMEO_AND_JULIET, $overrides);
    }

    public static function donQuijote(...$overrides): BorrowLine
    {
        return self::create(self::DON_QUIJOTE, $overrides);
    }

    protected static function create(array $values, array $overrides = []): BorrowLine
    {
        $fields = self::merge($values, $overrides);
        extract($fields);

        return new BorrowLine($borrow, $book);
    }
}
