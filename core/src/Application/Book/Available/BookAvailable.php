<?php declare(strict_types=1);

namespace App\Application\Book\Available;

use App\Domain\Book\BookRepository;
use App\Domain\Book\Exception\BookNotFoundException;
use App\Domain\Services\BookInspector\BookInspectFactory;

final readonly class BookAvailable
{
    public function __construct(
        private BookRepository $repoBook,
        private BookInspectFactory $factory,
    ) {}

    public function dispatch(string $bookId, bool $isSale): bool
    {
        $book = $this->repoBook->obtainById($bookId);
        if (null === $book) {
            throw new BookNotFoundException();
        }

        $inspector = $this->factory->create($isSale);

        return $inspector->available($book);
    }
}
