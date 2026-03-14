<?php

namespace App\Domain\Services\BookInspector;

use App\Domain\Book\Book;
use App\Domain\Borrow\BorrowLineCollection;
use App\Domain\Borrow\BorrowLineRepository;

abstract class BookInspector
{
    public function __construct(
        private readonly BorrowLineRepository $repoLineBorrow,
    ) {}

    public abstract function available(Book $book): bool;

    protected function obtainActiveBorrows(Book $book): BorrowLineCollection
    {
        return $this->repoLineBorrow->obtainActiveByBook($book->getId());
    }
}
