<?php

namespace App\Tests\Domain\Services\BookInspector;

use App\Domain\Services\BookInspector\BookBorrowInspect;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowLineRepositoryStub;
use App\Tests\Fixtures\Mothers\BookMother;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;

class BookBorrowInspectTest extends TestCase
{
    private BorrowLineRepositoryStub $repoBorrowLine;
    private BookBorrowInspect $inspector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoBorrowLine = new BorrowLineRepositoryStub();
        $this->inspector = new BookBorrowInspect($this->repoBorrowLine);
    }

    public function testShouldFalseWhenNotStock(): void
    {
        $book = BookMother::romeoAndJuliet();

        $result = $this->inspector->available($book);

        self::assertFalse($result);
    }

    public function testShouldTrueWhenHasStockNotActiveBorrows(): void
    {
        $book = BookMother::romeoAndJuliet();
        $book->changeStock(3);

        $result = $this->inspector->available($book);

        self::assertTrue($result);
    }

    public function testShouldTrueWhenHasStockAndActiveBorrows(): void
    {
        $this->repoBorrowLine->attach(Ref::BorrowLineJohnQuijote);

        $book = BookMother::romeoAndJuliet();
        $book->changeStock(3);

        $result = $this->inspector->available($book);

        self::assertTrue($result);
    }

    public function testShouldFalseWhenTheStockIsEqualsToActiveBorrows(): void
    {
        $this->repoBorrowLine->attach(Ref::BorrowLineJohnQuijote);
        $this->repoBorrowLine->attach(Ref::BorrowLineJohnRomeoAndJuliet);

        $book = BookMother::romeoAndJuliet();
        $book->changeStock(2);

        $result = $this->inspector->available($book);

        self::assertFalse($result);
    }
}
