<?php declare(strict_types=1);

namespace App\Application\Author\Eraser;

use App\Domain\Author\Author;
use App\Domain\Author\AuthorRepository;
use App\Domain\Author\Exception\AuthorNotFoundException;
use App\Domain\Book\BookRepository;

final readonly class AuthorDelete
{
    public function __construct(
        private AuthorRepository $repoAuthor,
        private BookRepository $repoBook,
    ) {}

    public function dispatch(string $authorId): void
    {
        $author = $this->repoAuthor->obtainById($authorId);
        if (null === $author) {
            throw new AuthorNotFoundException();
        }

        $this->searchBooksRelated($author);

        $this->repoAuthor->remove($author);
    }

    private function searchBooksRelated(Author $author): void
    {
        $books = $this->repoBook->obtainByAuthor($author->getId(), 1);
        if (!$books->isEmpty()) {
            throw new AuthorBookAssociatedException();
        }
    }
}
