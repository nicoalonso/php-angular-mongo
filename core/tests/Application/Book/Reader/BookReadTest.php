<?php

namespace App\Tests\Application\Book\Reader;

use App\Application\Book\Reader\BookRead;
use App\Domain\Book\Exception\BookNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;

class BookReadTest extends TestCase
{
    private BookRepositoryStub $repoBook;
    private BookRead $reader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoBook = new BookRepositoryStub();
        $this->reader = new BookRead($this->repoBook);
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(BookNotFoundException::class);

        $this->reader->dispatch('unknown-book-id');
    }

    public function testShouldRunWhenRead(): void
    {
        $this->repoBook->put(Ref::BookDonQuijote);

        $book = $this->reader->dispatch('1234567890');

        self::assertEquals('Don Quijote', $book->getTitle());
    }
}
