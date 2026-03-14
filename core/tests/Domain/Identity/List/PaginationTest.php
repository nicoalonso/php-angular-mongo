<?php

namespace App\Tests\Domain\Identity\List;

use App\Domain\Identity\List\Pagination;
use PHPUnit\Framework\TestCase;

class PaginationTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $pagination = new Pagination();

        self::assertEquals(0, $pagination->getTotal());
        self::assertEquals(1, $pagination->getPage());
        self::assertEquals(10, $pagination->getRowsPerPage());
        self::assertEquals(0, $pagination->getTotalPages());
    }

    public function testShouldRunWhenIsValid(): void
    {
        $pagination = new Pagination(235, 4, 15);

        self::assertEquals(235, $pagination->getTotal());
        self::assertEquals(4, $pagination->getPage());
        self::assertEquals(15, $pagination->getRowsPerPage());
        self::assertEquals(16, $pagination->getTotalPages());
    }
}
