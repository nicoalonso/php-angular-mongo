<?php

namespace App\Tests\Domain\Borrow;

use App\Domain\Borrow\BorrowCollection;
use App\Tests\Domain\Book\BookCollectionTest;
use App\Tests\Fixtures\Mothers\BorrowMother;
use PHPUnit\Framework\TestCase;

class BorrowCollectionTest extends TestCase
{
    public function testShouldRunWhenAddBorrow(): void
    {
        $collection = new BorrowCollection();

        $borrow = BorrowMother::johnDoe();
        $collection->add($borrow);

        $this->assertCount(1, $collection);
    }
}
