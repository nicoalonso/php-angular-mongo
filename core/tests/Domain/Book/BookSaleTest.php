<?php

namespace App\Tests\Domain\Book;

use App\Domain\Book\BookSale;
use PHPUnit\Framework\TestCase;

class BookSaleTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $sale = new BookSale(
            true,
            19.99,
            5.00
        );

        self::assertTrue($sale->isSaleable());
        self::assertEquals(19.99, $sale->getPrice());
        self::assertEquals(5.00, $sale->getDiscount());
    }
}
