<?php declare(strict_types=1);

namespace App\Application\Book\Creator;

use App\Domain\Author\AuthorRepository;
use App\Domain\Book\Book;
use App\Domain\Book\BookRepository;
use App\Domain\Book\Exception\BookAlreadyExistsException;
use App\Domain\Editorial\EditorialRepository;
use App\Domain\User\UserRepository;

final readonly class BookCreate
{
    use BookMakeable;

    public function __construct(
        private BookRepository $repoBook,
        private AuthorRepository $repoAuthor,
        private EditorialRepository $repoEditorial,
        private UserRepository $repoUser,
    ) {}

    public function dispatch(BookCreatePayload $payload): Book
    {
        $this->checkAlreadyExists($payload);

        $author = $this->findAuthor($payload->getAuthorId());
        $editorial = $this->findEditorial($payload->getEditorialId());

        $detail = $this->makeDetail($payload->getDetail());
        $sale = $this->makeSale($payload->getSale());

        $user = $this->repoUser->obtainUser();
        $book = new Book(
            $payload->getTitle(),
            $payload->getDescription(),
            $author,
            $editorial,
            $detail,
            $sale,
            $user->getName(),
        );
        $this->repoBook->save($book);

        return $book;
    }

    private function checkAlreadyExists(BookCreatePayload $payload): void
    {
        $book = $this->repoBook->obtainByTitle($payload->getTitle());
        if (null !== $book) {
            throw new BookAlreadyExistsException();
        }
    }
}
