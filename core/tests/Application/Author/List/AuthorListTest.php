<?php

namespace App\Tests\Application\Author\List;

use App\Application\Author\List\AuthorList;
use App\Domain\Identity\List\Exception\InvalidFilterException;
use App\Domain\Identity\List\Exception\InvalidSortFieldException;
use App\Domain\Identity\List\ListQuery;
use App\Tests\Doubles\Infrastructure\Persistence\AuthorRepositoryStub;
use PHPUnit\Framework\TestCase;

class AuthorListTest extends TestCase
{
    private AuthorRepositoryStub $repository;
    private AuthorList $lister;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new AuthorRepositoryStub();
        $this->lister = new AuthorList($this->repository);
    }

    public function testShouldRunWhenList(): void
    {
        $this->repository->attachAll();

        $query = new ListQuery();
        $result = $this->lister->dispatch($query);

        self::assertGreaterThanOrEqual(1, $result->items()->count());
    }

    public function testShouldFailWhenInvalidFilter(): void
    {
        $params = ['test' => 'value'];
        $query = ListQuery::fromParams($params);

        $this->expectException(InvalidFilterException::class);
        $this->lister->dispatch($query);
    }

    public function testShouldFailWhenInvalidSortField(): void
    {
        $params = ['sort' => 'test'];
        $query = ListQuery::fromParams($params);

        $this->expectException(InvalidSortFieldException::class);
        $this->lister->dispatch($query);
    }

    public function testShouldRunWhenEmpty(): void
    {
        $query = new ListQuery();
        $result = $this->lister->dispatch($query);

        self::assertEmpty($result->items());
    }
}
