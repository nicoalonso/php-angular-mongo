<?php

namespace App\Tests\Application\Book\List;

use App\Application\Book\List\BookList;
use App\Domain\Identity\List\ListQuery;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use PHPUnit\Framework\TestCase;

class BookListTest extends TestCase
{
    private BookRepositoryStub $repository;
    private BookList $lister;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new BookRepositoryStub();
        $this->lister = new BookList($this->repository);
    }

    public function testShouldRunWhenList(): void
    {
        $this->repository->attachAll();

        $query = new ListQuery();
        $result = $this->lister->dispatch($query);

        self::assertGreaterThanOrEqual(1, $result->items()->count());
    }
}
