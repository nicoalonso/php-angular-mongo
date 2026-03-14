<?php

namespace App\Tests\Presentation\Identity;

use App\Domain\Identity\List\Pagination;
use App\Presentation\Identity\PaginationView;
use PHPUnit\Framework\TestCase;

class PaginationViewTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $pagination = new Pagination(2, 1, 10);
        $view = new PaginationView($pagination);
        $json = $view->jsonSerialize();

        self::assertEquals(2, $json['total']);
        self::assertEquals(1, $json['page']);
        self::assertEquals(10, $json['rowsPerPage']);
        self::assertEquals(1, $json['totalPages']);
    }
}
