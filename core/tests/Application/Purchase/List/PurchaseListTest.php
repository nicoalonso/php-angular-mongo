<?php

namespace App\Tests\Application\Purchase\List;

use App\Application\Purchase\List\PurchaseList;
use App\Domain\Identity\List\ListQuery;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseRepositoryStub;
use PHPUnit\Framework\TestCase;

class PurchaseListTest extends TestCase
{
    private PurchaseRepositoryStub $repository;
    private PurchaseList $lister;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new PurchaseRepositoryStub();
        $this->lister = new PurchaseList($this->repository);
    }

    public function testShouldRunWhenList(): void
    {
        $this->repository->attachAll();

        $query = new ListQuery();
        $result = $this->lister->dispatch($query);

        self::assertGreaterThanOrEqual(1, $result->items()->count());
    }
}
