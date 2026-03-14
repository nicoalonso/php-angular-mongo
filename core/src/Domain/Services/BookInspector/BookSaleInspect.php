<?php declare(strict_types=1);

namespace App\Domain\Services\BookInspector;

use App\Domain\Book\Book;

final class BookSaleInspect extends BookInspector
{
    private const int MIN_STOCK_FOR_SALE = 3;

    public function available(Book $book): bool
    {
        $activeBorrows = $this->obtainActiveBorrows($book)->count();
        $availableStock = $book->getStock() - $activeBorrows;

        return $availableStock >= self::MIN_STOCK_FOR_SALE;
    }
}
