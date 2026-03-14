<?php declare(strict_types=1);

namespace App\Domain\Book;

use App\Domain\Identity\AbstractCollection;

/**
 * @template-extends AbstractCollection<Book>
 */
final class BookCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Book::class;
    }
}
