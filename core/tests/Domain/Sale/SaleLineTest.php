<?php

namespace App\Tests\Domain\Sale;

use App\Domain\Book\Book;
use App\Domain\Sale\Sale;
use App\Domain\Sale\SaleLine;
use App\Tests\Fixtures\Mothers\BookMother;
use App\Tests\Fixtures\Mothers\SaleMother;
use PHPUnit\Framework\TestCase;

class SaleLineTest extends TestCase
{
    private Sale $sale;
    private Book $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sale = SaleMother::johnDoeSale2();
        $this->book = BookMother::donQuijote();
    }

    public function testShouldRunWhenCreate(): void
    {
        $line = new SaleLine(
            $this->sale,
            $this->book,
            2,
            10.0,
            0.0,
            20.0
        );

        self::assertEquals($this->sale, $line->getSale());
        self::assertEquals($this->book->getDescriptor(), $line->getBook());
        self::assertEquals(2, $line->getQuantity());
        self::assertEquals(10.0, $line->getPrice());
        self::assertEquals(0.0, $line->getDiscount());
        self::assertEquals(20.0, $line->getTotal());
    }

    public function testShouldRunWhenModify(): void
    {
        $line = new SaleLine(
            $this->sale,
            $this->book,
            2,
            10.0,
            0.0,
            20.0
        );

        $book2 = BookMother::romeoAndJuliet();

        $line->modify(
            $book2,
            3,
            15.0,
            5.0,
            40.0
        );

        self::assertEquals($book2->getDescriptor(), $line->getBook());
        self::assertEquals(3, $line->getQuantity());
        self::assertEquals(15.0, $line->getPrice());
        self::assertEquals(5.0, $line->getDiscount());
        self::assertEquals(40.0, $line->getTotal());
    }
}
