<?php

namespace App\Tests\Domain\Identity\List;

use App\Domain\Identity\List\ListResult;
use App\Domain\Identity\List\Pagination;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class ListResultTest extends TestCase
{
    public function testShouldRunWhenEmpty(): void
    {
        $result = new ListResult();

        self::assertEmpty($result->items());
        self::assertEquals(1, $result->pagination()->getPage());
    }

    public function testShouldRunWhenCreate(): void
    {
        $col = new ArrayCollection();
        $pagination = new Pagination();
        $result = new ListResult($col, $pagination);

        self::assertEmpty($result->items());
        self::assertEquals(1, $result->pagination()->getPage());
    }
}
