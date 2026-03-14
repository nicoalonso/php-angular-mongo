<?php declare(strict_types=1);

namespace App\Application\Book\Eraser;

use App\Domain\Book\BookRepository;
use App\Domain\Book\Exception\BookNotFoundException;
use App\Domain\Borrow\BorrowLineRepository;
use App\Domain\Purchase\PurchaseLineRepository;
use App\Domain\Sale\SaleLineRepository;

final readonly class BookDelete
{
    public function __construct(
        private BookRepository $repoBook,
        private PurchaseLineRepository $repoPurchaseLine,
        private SaleLineRepository $repoSaleLine,
        private BorrowLineRepository $repoBorrowLine,
    ) {}

    public function dispatch(string $bookId): void
    {
        $book = $this->repoBook->obtainById($bookId);
        if (null === $book) {
            throw new BookNotFoundException();
        }

        $this->checkAssociated($bookId);

        $this->repoBook->remove($book);
    }

    private function checkAssociated(string $bookId): void
    {
        $lines = $this->repoPurchaseLine->obtainByBook($bookId, 1);
        if (!$lines->isEmpty()) {
            throw new BookAssociatedException();
        }

        $lines = $this->repoBorrowLine->obtainByBook($bookId, 1);
        if (!$lines->isEmpty()) {
            throw new BookAssociatedException('The book is associated with one or more borrows.');
        }

        $lines = $this->repoSaleLine->obtainByBook($bookId, 1);
        if (!$lines->isEmpty()) {
            throw new BookAssociatedException('The book is associated with one or more sales.');
        }
    }
}
