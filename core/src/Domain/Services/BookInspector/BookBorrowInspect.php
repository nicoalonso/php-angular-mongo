<?php declare(strict_types=1);

namespace App\Domain\Services\BookInspector;

use App\Domain\Book\Book;

final class BookBorrowInspect extends BookInspector
{
    public function available(Book $book): bool
    {
        $activeBorrows = $this->obtainActiveBorrows($book)->count();

        return $book->getStock() > $activeBorrows;
    }
}
