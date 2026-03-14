<?php

namespace App\Tests\Application\Sale\List;

use App\Application\Sale\List\SaleList;
use App\Domain\Identity\List\ListQuery;
use App\Tests\Doubles\Infrastructure\Persistence\SaleRepositoryStub;
use PHPUnit\Framework\TestCase;

class SaleListTest extends TestCase
{
    private SaleRepositoryStub $repository;
    private SaleList $lister;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new SaleRepositoryStub();
        $this->lister = new SaleList($this->repository);
    }

    public function testShouldRunWhenList(): void
    {
        $this->repository->attachAll();

        $query = new ListQuery();
        $result = $this->lister->dispatch($query);

        self::assertGreaterThanOrEqual(1, $result->items()->count());
    }
}
