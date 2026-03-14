<?php declare(strict_types=1);

namespace App\Application\Editorial\Eraser;

use App\Domain\Book\BookRepository;
use App\Domain\Editorial\Editorial;
use App\Domain\Editorial\EditorialRepository;
use App\Domain\Editorial\Exception\EditorialNotFoundException;

final readonly class EditorialDelete
{
    public function __construct(
        private EditorialRepository $repoEditorial,
        private BookRepository $repoBook,
    ) {}

    public function dispatch(string $editorialId): void
    {
        $editorial = $this->repoEditorial->obtainById($editorialId);
        if (null === $editorial) {
            throw new EditorialNotFoundException();
        }

        $this->searchBooksRelated($editorial);

        $this->repoEditorial->remove($editorial);
    }

    private function searchBooksRelated(Editorial $editorial): void
    {
        $books = $this->repoBook->obtainByEditorial($editorial->getId(), 1);
        if (!$books->isEmpty()) {
            throw new EditorialBookAssociatedException();
        }
    }
}
