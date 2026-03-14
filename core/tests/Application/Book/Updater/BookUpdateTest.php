<?php

namespace App\Tests\Application\Book\Updater;

use App\Application\Book\Updater\BookUpdate;
use App\Application\Book\Updater\BookUpdatePayload;
use App\Domain\Book\Exception\BookNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\AuthorRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\EditorialRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class BookUpdateTest extends TestCase
{
    use FixturePayload;

    private BookRepositoryStub $repoBook;
    private AuthorRepositoryStub $repoAuthor;
    private EditorialRepositoryStub $repoEditorial;
    private BookUpdate $updater;
    private BookUpdatePayload $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoAuthor = new AuthorRepositoryStub();
        $this->repoEditorial = new EditorialRepositoryStub();
        $this->repoBook = new BookRepositoryStub($this->repoAuthor, $this->repoEditorial);
        $repoUser = new UserRepositoryStub();

        $this->updater = new BookUpdate(
            $this->repoBook,
            $this->repoAuthor,
            $this->repoEditorial,
            $repoUser,
        );

        $data = $this->getPayload('book-create');
        $this->payload = new BookUpdatePayload($data);
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(BookNotFoundException::class);
        $this->updater->dispatch('invalid-id', $this->payload);
    }

    public function testShouldRunWhenUpdate(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $this->repoAuthor->put(Ref::AuthorShakespeare);
        $this->repoEditorial->put(Ref::EditorialAnaya);

        $this->updater->dispatch('12345687', $this->payload);

        assertStored($this->repoBook);
    }
}
