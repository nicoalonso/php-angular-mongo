<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers;

use App\Domain\Book\Book;
use App\Tests\Fixtures\Mothers\Base\BaseMother;

final class BookMother extends BaseMother
{
    private const array ROMEO_AND_JULIET = [
        'title' => 'Romeo and Juliet',
        'description' => 'Romeo and Juliet is a tragedy written by William Shakespeare early in his career about two young star-crossed lovers whose deaths ultimately reconcile their feuding families.',
        'author' => [AuthorMother::class, 'shakespeare'],
        'editorial' => [EditorialMother::class, 'anaya'],
        'detail' => [BookDetailMother::class, 'valid'],
        'sale' => [BookSaleMother::class, 'valid'],
        'createdBy' => 'test',
    ];

    private const array DON_QUIJOTE = [
        'title' => 'Don Quijote',
        'description' => 'Don Quijote de la Mancha is a Spanish novel by Miguel de Cervantes. It follows the adventures of a nobleman who reads so many chivalric romances that he loses his sanity and decides to become a knight-errant, reviving chivalry and serving his nation.',
        'author' => [AuthorMother::class, 'cervantes'],
        'editorial' => [EditorialMother::class, 'anaya'],
        'detail' => [BookDetailMother::class, 'quijote'],
        'sale' => [BookSaleMother::class, 'valid'],
        'createdBy' => 'test',
    ];

    public static function romeoAndJuliet(...$overrides): Book
    {
        return self::create(self::ROMEO_AND_JULIET, $overrides);
    }

    public static function donQuijote(...$overrides): Book
    {
        return self::create(self::DON_QUIJOTE, $overrides);
    }

    protected static function create(array $values, array $overrides = []): Book
    {
        $fields = self::merge($values, $overrides);
        extract($fields);

        return new Book(
            $title,
            $description,
            $author,
            $editorial,
            $detail,
            $sale,
            $createdBy,
        );
    }
}
