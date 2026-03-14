<?php

namespace App\Tests\Application\Borrow\List;

use App\Application\Borrow\List\BorrowList;
use App\Domain\Identity\List\ListQuery;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowRepositoryStub;
use PHPUnit\Framework\TestCase;

class BorrowListTest extends TestCase
{
    private BorrowRepositoryStub $repository;
    private BorrowList $lister;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new BorrowRepositoryStub();
        $this->lister = new BorrowList($this->repository);
    }

    public function testShouldRunWhenList(): void
    {
        $this->repository->attachAll();

        $query = new ListQuery();
        $result = $this->lister->dispatch($query);

        self::assertGreaterThanOrEqual(1, $result->items()->count());
    }
}
