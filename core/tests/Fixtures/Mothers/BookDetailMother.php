<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers;

use App\Domain\Book\BookDetail;
use App\Tests\Fixtures\Mothers\Base\BaseMother;
use App\Tests\Fixtures\Mothers\Base\MotherMapping;

final class BookDetailMother extends BaseMother
{
    private const array VALID = [
        'edition' => '001',
        'isbn' => '978-1234567890',
        'language' => 'English',
        'publishedAt' => ['2020-01-01', MotherMapping::DATE_IMMUTABLE],
        'pages' => 100,
    ];

    private const array QUIJOTE = [
        'edition' => '001',
        'isbn' => '978-1234567890',
        'language' => 'Spanish',
        'publishedAt' => ['1615-01-01', MotherMapping::DATE_IMMUTABLE],
        'pages' => 100,
    ];

    public static function valid(...$overrides): BookDetail
    {
        return self::create(self::VALID, $overrides);
    }

    public static function quijote(...$overrides): BookDetail
    {
        return self::create(self::QUIJOTE, $overrides);
    }

    protected static function create(array $values, array $overrides = []): BookDetail
    {
        $fields = self::merge($values, $overrides);
        extract($fields);

        return new BookDetail(
            $edition,
            $isbn,
            $language,
            $publishedAt,
            $pages,
        );
    }
}
