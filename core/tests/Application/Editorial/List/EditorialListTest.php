<?php

namespace App\Tests\Application\Editorial\List;

use App\Application\Editorial\List\EditorialList;
use App\Domain\Identity\List\ListQuery;
use App\Tests\Doubles\Infrastructure\Persistence\EditorialRepositoryStub;
use PHPUnit\Framework\TestCase;

class EditorialListTest extends TestCase
{
    private EditorialRepositoryStub $repository;
    private EditorialList $lister;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new EditorialRepositoryStub();
        $this->lister = new EditorialList($this->repository);
    }

    public function testShouldRunWhenList(): void
    {
        $this->repository->attachAll();

        $query = new ListQuery();
        $result = $this->lister->dispatch($query);

        self::assertGreaterThanOrEqual(1, $result->items()->count());
    }
}
