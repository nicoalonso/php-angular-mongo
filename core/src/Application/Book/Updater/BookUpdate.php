<?php declare(strict_types=1);

namespace App\Application\Book\Updater;

use App\Application\Book\Creator\BookMakeable;
use App\Domain\Author\AuthorRepository;
use App\Domain\Book\Book;
use App\Domain\Book\BookRepository;
use App\Domain\Book\Exception\BookNotFoundException;
use App\Domain\Editorial\EditorialRepository;
use App\Domain\User\UserRepository;

final readonly class BookUpdate
{
    use BookMakeable;

    public function __construct(
        private BookRepository $repoBook,
        private AuthorRepository $repoAuthor,
        private EditorialRepository $repoEditorial,
        private UserRepository $repoUser,
    ) {}

    public function dispatch(string $bookId, BookUpdatePayload $payload): Book
    {
        $book = $this->findBook($bookId);

        $author = $this->findAuthor($payload->getAuthorId());
        $editorial = $this->findEditorial($payload->getEditorialId());

        $detail = $this->makeDetail($payload->getDetail());
        $sale = $this->makeSale($payload->getSale());

        $user = $this->repoUser->obtainUser();

        $book->modify(
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

    private function findBook(string $bookId): Book
    {
        $book = $this->repoBook->obtainById($bookId);
        if (null === $book) {
            throw new BookNotFoundException();
        }

        return $book;
    }
}