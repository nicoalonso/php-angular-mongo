<?php

namespace App\Tests\Application\Provider\List;

use App\Application\Provider\List\ProviderList;
use App\Domain\Identity\List\ListQuery;
use App\Tests\Doubles\Infrastructure\Persistence\ProviderRepositoryStub;
use PHPUnit\Framework\TestCase;

class ProviderListTest extends TestCase
{
    private ProviderRepositoryStub $repository;
    private ProviderList $lister;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new ProviderRepositoryStub();
        $this->lister = new ProviderList($this->repository);
    }

    public function testShouldRunWhenList(): void
    {
        $this->repository->attachAll();

        $query = new ListQuery();
        $result = $this->lister->dispatch($query);

        self::assertGreaterThanOrEqual(1, $result->items()->count());
    }
}
