<?php

namespace App\Tests\Domain\Book;

use App\Domain\Book\BookCollection;
use App\Tests\Fixtures\Mothers\BookMother;
use PHPUnit\Framework\TestCase;

class BookCollectionTest extends TestCase
{
    public function testShouldRunWhenAddBook(): void
    {
        $collection = new BookCollection();

        $book = BookMother::romeoAndJuliet();
        $collection->add($book);

        $this->assertCount(1, $collection);
    }
}
