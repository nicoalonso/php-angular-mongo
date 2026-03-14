<?php declare(strict_types=1);

namespace App\Application\Book\Reader;

use App\Domain\Book\Book;
use App\Domain\Book\BookRepository;
use App\Domain\Book\Exception\BookNotFoundException;

final readonly class BookRead
{
    public function __construct(private BookRepository $repoBook) {}

    public function dispatch(string $bookId): Book
    {
        $book = $this->repoBook->obtainById($bookId);
        if (null === $book) {
            throw new BookNotFoundException();
        }

        return $book;
    }
}
