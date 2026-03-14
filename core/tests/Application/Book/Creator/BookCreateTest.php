<?php

namespace App\Tests\Application\Book\Creator;

use App\Application\Book\Creator\BookCreate;
use App\Application\Book\Creator\BookCreatePayload;
use App\Domain\Author\Exception\AuthorNotFoundException;
use App\Domain\Book\Exception\BookAlreadyExistsException;
use App\Domain\Book\Exception\InvalidPublishedDateException;
use App\Domain\Editorial\Exception\EditorialNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\AuthorRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\EditorialRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class BookCreateTest extends TestCase
{
    use FixturePayload;

    private BookRepositoryStub $repoBook;
    private AuthorRepositoryStub $repoAuthor;
    private EditorialRepositoryStub $repoEditorial;
    private BookCreate $creator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoAuthor = new AuthorRepositoryStub();
        $this->repoEditorial = new EditorialRepositoryStub();
        $this->repoBook = new BookRepositoryStub($this->repoAuthor, $this->repoEditorial);
        $repoUser = new UserRepositoryStub();

        $this->creator = new BookCreate(
            $this->repoBook,
            $this->repoAuthor,
            $this->repoEditorial,
            $repoUser,
        );
    }

    public function testShouldFailWhenAlreadyExists(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);

        $data = $this->getPayload('book-create');
        $payload = new BookCreatePayload($data);

        $this->expectException(BookAlreadyExistsException::class);
        $this->creator->dispatch($payload);
    }

    public function testShouldFailWhenAuthorNotExists(): void
    {
        $data = $this->getPayload('book-create');
        $payload = new BookCreatePayload($data);

        $this->expectException(AuthorNotFoundException::class);
        $this->creator->dispatch($payload);
    }

    public function testShouldFailWhenEditorialNotFound(): void
    {
        $this->repoAuthor->put(Ref::AuthorShakespeare);

        $data = $this->getPayload('book-create');
        $payload = new BookCreatePayload($data);

        $this->expectException(EditorialNotFoundException::class);
        $this->creator->dispatch($payload);
    }

    public function testShouldFailWhenInvalidPublishedAt(): void
    {
        $this->repoAuthor->put(Ref::AuthorShakespeare);
        $this->repoEditorial->put(Ref::EditorialAnaya);

        $data = $this->override(detail: [])
            ->getPayload('book-create');
        $payload = new BookCreatePayload($data);

        $this->expectException(InvalidPublishedDateException::class);
        $this->creator->dispatch($payload);
    }

    public function testShouldRunWhenCreate(): void
    {
        $this->repoAuthor->put(Ref::AuthorShakespeare);
        $this->repoEditorial->put(Ref::EditorialAnaya);

        $data = $this->getPayload('book-create');
        $payload = new BookCreatePayload($data);

        $book = $this->creator->dispatch($payload);

        self::assertEquals('Romeo and Juliet', $book->getTitle());
        assertStored($this->repoBook);
    }
}
