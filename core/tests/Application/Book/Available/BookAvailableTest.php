<?php

namespace App\Tests\Application\Book\Available;

use App\Application\Book\Available\BookAvailable;
use App\Domain\Book\Exception\BookNotFoundException;
use App\Domain\Services\BookInspector\BookInspectFactory;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowLineRepositoryStub;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;

class BookAvailableTest extends TestCase
{
    private BookRepositoryStub $repoBook;
    private BookAvailable $available;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoBook = new BookRepositoryStub();
        $repoBorrowLine = new BorrowLineRepositoryStub(repoBook: $this->repoBook);
        $factory = new BookInspectFactory($repoBorrowLine);
        $this->available = new BookAvailable($this->repoBook, $factory);
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(BookNotFoundException::class);
        $this->available->dispatch('non-existing-id', true);
    }

    public function testShouldRunWhenAvailable(): void
    {
        $book = $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $book->changeStock(10);

        $result = $this->available->dispatch('123456', true);

        self::assertTrue($result);
    }
}
