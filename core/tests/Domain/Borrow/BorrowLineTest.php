<?php

namespace App\Tests\Domain\Borrow;

use App\Domain\Book\Book;
use App\Domain\Borrow\Borrow;
use App\Domain\Borrow\BorrowLine;
use App\Tests\Fixtures\Mothers\BookMother;
use App\Tests\Fixtures\Mothers\BorrowMother;
use PHPUnit\Framework\TestCase;

class BorrowLineTest extends TestCase
{
    private Borrow $borrow;
    private Book $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->borrow = BorrowMother::johnDoe();
        $this->book = BookMother::romeoAndJuliet();
    }

    public function testShouldRunWhenCreate(): void
    {
        $line = new BorrowLine($this->borrow, $this->book);

        $this->assertSame($this->borrow, $line->getBorrow());
        $this->assertEquals($this->book->getDescriptor(), $line->getBook());
        $this->assertFalse($line->isReturned());
        $this->assertNull($line->getReturnedDate());
        self::assertFalse($line->hasPenalty());
        self::assertEquals(0.0, $line->getPenaltyAmount());
    }

    public function testShouldRunWhenCheckIn(): void
    {
        $line = new BorrowLine($this->borrow, $this->book);
        $line->checkIn();

        $this->assertTrue($line->isReturned());
        $this->assertNotNull($line->getReturnedDate());
    }

    public function testShouldRunWhenPenalize(): void
    {
        $line = new BorrowLine($this->borrow, $this->book);
        $line->penalize(5.0);

        self::assertTrue($line->hasPenalty());
        self::assertEquals(5.0, $line->getPenaltyAmount());
    }
}
