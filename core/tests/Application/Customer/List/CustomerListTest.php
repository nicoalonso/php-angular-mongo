<?php

namespace App\Tests\Application\Customer\List;

use App\Application\Customer\List\CustomerList;
use App\Domain\Identity\List\ListQuery;
use App\Tests\Doubles\Infrastructure\Persistence\CustomerRepositoryStub;
use PHPUnit\Framework\TestCase;

class CustomerListTest extends TestCase
{
    private CustomerRepositoryStub $repository;
    private CustomerList $lister;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new CustomerRepositoryStub();
        $this->lister = new CustomerList($this->repository);
    }

    public function testShouldRunWhenList(): void
    {
        $this->repository->attachAll();

        $query = new ListQuery();
        $result = $this->lister->dispatch($query);

        self::assertGreaterThanOrEqual(1, $result->items()->count());
    }
}
