<?php

namespace App\Tests\Domain\Purchase;

use App\Domain\Book\Book;
use App\Domain\Purchase\Purchase;
use App\Domain\Purchase\PurchaseLine;
use App\Tests\Fixtures\Mothers\BookMother;
use App\Tests\Fixtures\Mothers\PurchaseMother;
use PHPUnit\Framework\TestCase;

class PurchaseLineTest extends TestCase
{
    private Purchase $purchase;
    private Book $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->purchase = PurchaseMother::amazonInv1();
        $this->book = BookMother::romeoAndJuliet();
    }

    public function testShouldRunWhenCreate(): void
    {
        $line = new PurchaseLine(
            $this->purchase,
            $this->book,
            2,
            10.0,
            5.0,
            19.0
        );

        self::assertEquals($this->purchase, $line->getPurchase());
        self::assertEquals($this->book->getDescriptor(), $line->getBook());
        self::assertEquals(2, $line->getQuantity());
        self::assertEquals(10.0, $line->getUnitPrice());
        self::assertEquals(5.0, $line->getDiscountPercentage());
        self::assertEquals(19.0, $line->getTotal());
    }

    public function testShouldRunWhenModify(): void
    {
        $line = new PurchaseLine(
            $this->purchase,
            $this->book,
            2,
            10.0,
            5.0,
            19.0
        );

        $book2 = BookMother::donQuijote();
        $line->modify(
            $book2,
            3,
            15.0,
            10.0,
            40.5
        );

        self::assertEquals($book2->getDescriptor(), $line->getBook());
        self::assertEquals(3, $line->getQuantity());
        self::assertEquals(15.0, $line->getUnitPrice());
        self::assertEquals(10.0, $line->getDiscountPercentage());
        self::assertEquals(40.5, $line->getTotal());
    }
}
