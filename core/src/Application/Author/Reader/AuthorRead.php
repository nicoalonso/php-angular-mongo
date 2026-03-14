<?php declare(strict_types=1);

namespace App\Application\Author\Reader;

use App\Domain\Author\Author;
use App\Domain\Author\AuthorRepository;
use App\Domain\Author\Exception\AuthorNotFoundException;

final readonly class AuthorRead
{
    public function __construct(private AuthorRepository $repoAuthor) {}

    public function dispatch(string $authorId): Author
    {
        $author = $this->repoAuthor->obtainById($authorId);
        if (null === $author) {
            throw new AuthorNotFoundException();
        }

        return $author;
    }
}
