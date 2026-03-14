<?php

namespace App\Tests\Domain\Identity\List;

use App\Domain\Identity\List\Field;
use App\Domain\Identity\List\FieldCollection;
use App\Domain\Identity\List\FilterField;
use App\Domain\Identity\List\ListQuery;
use App\Domain\Identity\List\SortField;
use PHPUnit\Framework\TestCase;

class ListQueryTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $query = new ListQuery();

        self::assertEmpty($query->joins());
        self::assertEmpty($query->filters());
        self::assertEmpty($query->sort());
        self::assertEquals(1, $query->page());
        self::assertEquals(10, $query->limit());
        self::assertEquals(0, $query->offset());

        self::assertFalse($query->hasJoins());
        self::assertFalse($query->hasFilters());
        self::assertFalse($query->hasSort());
    }

    public function testShouldValidWhenFromParamsWithFilters(): void
    {
        $params = [
            'test1' => 'value1',
            'test2' => 'value2',
        ];
        $query = ListQuery::fromParams($params);

        self::assertTrue($query->hasFilters());
        self::assertCount(2, $query->filters());
        self::assertEquals('test1', $query->filters()->first()->name());
        self::assertEquals('value1', $query->filters()->first()->value());
    }

    public function testShouldNullWhenNotFoundFilter(): void
    {
        $params = [
            'test1' => 'value1',
            'test2' => 'value2',
        ];
        $query = ListQuery::fromParams($params);

        self::assertNull($query->getFilter('dummy'));
    }

    public function testShouldNotNullWhenFoundFilter(): void
    {
        $params = [
            'test1' => 'value1',
            'test2' => 'value2',
        ];
        $query = ListQuery::fromParams($params);

        self::assertEquals('value1', $query->getFilter('test1')?->value());
    }

    public function testShouldRunWhenAddFilter(): void
    {
        $query = new ListQuery();
        $filter = new FilterField('test1', 'value1');
        $query->addFilter($filter);

        self::assertCount(1, $query->filters());
        self::assertNotNull($query->getFilter('test1'));
    }

    public function testShouldValidWhenFromParamsWithSort(): void
    {
        $sort = '+field1,-field2';
        $params = compact('sort');
        $query = ListQuery::fromParams($params);

        self::assertTrue($query->hasSort());
        self::assertCount(2, $query->sort());
        self::assertEquals('field1', $query->sort()->first()->alias());
        self::assertEquals('asc', $query->sort()->first()->direction());
        self::assertEquals('field2', $query->sort()->last()->alias());
        self::assertEquals('desc', $query->sort()->last()->direction());
    }

    public function testShouldNullWhenNotFoundSortField(): void
    {
        $sort = '+field1,-field2';
        $params = compact('sort');
        $query = ListQuery::fromParams($params);

        self::assertNull($query->getSort('dummy'));
    }

    public function testShouldNotNullWhenFoundSortField(): void
    {
        $sort = '+field1,-field2';
        $params = compact('sort');
        $query = ListQuery::fromParams($params);

        self::assertEquals('desc', $query->getSort('field2')?->direction());
    }

    public function testShouldRunWhenSpaceBeforeField(): void
    {
        $sort = ' field1';
        $params = compact('sort');
        $query = ListQuery::fromParams($params);

        self::assertCount(1, $query->sort());
        self::assertEquals('field1', $query->sort()->first()?->name());
    }

    public function testShouldRunWhenAddSortField(): void
    {
        $query = new ListQuery();
        $field = new SortField('field1', SortField::DESC_ORDER);
        $query->addSort($field);

        self::assertCount(1, $query->sort());
        self::assertEquals('desc', $query->getSort('field1')?->direction());
    }

    public function testShouldValidWhenFromParamsWithPaginated(): void
    {
        $params = [
            'page' => '1',
            'limit' => '50',
        ];
        $query = ListQuery::fromParams($params);

        self::assertSame(1, $query->page());
        self::assertSame(50, $query->limit());
        self::assertEquals(0, $query->offset());
    }

    public function testShouldValidWhenFromParamsWithPageTwo(): void
    {
        $params = [
            'page' => '2',
            'limit' => '50',
        ];
        $query = ListQuery::fromParams($params);

        self::assertSame(2, $query->page());
        self::assertSame(50, $query->limit());
        self::assertEquals(50, $query->offset());
    }

    public function testShouldRunWhenHasJoins(): void
    {
        $params = [];
        $query = ListQuery::fromParams($params);
        $joinFields = new FieldCollection();
        $joinFields->add(new Field('d'));

        $query->addJoins($joinFields);

        self::assertTrue($query->hasJoins());
        self::assertCount(1, $query->joins());
    }

    public function testShouldRangeWhenValues(): void
    {
        $params = [
            'fromAge' => '18',
            'toAge' => '50',
        ];
        $query = ListQuery::fromParams($params);

        self::assertTrue($query->hasFilters());
        self::assertCount(1, $query->filters());
        self::assertEquals('age', $query->filters()->first()->name());
        self::assertEquals('18', $query->filters()->first()->value()->from());
        self::assertEquals('50', $query->filters()->first()->value()->to());
    }

    public function testShouldRunWhenRemoveFilter(): void
    {
        $params = [
            'test1' => 'value1',
            'test2' => 'value2',
        ];
        $query = ListQuery::fromParams($params);

        $query->removeFilter('test1');

        self::assertCount(1, $query->filters());
        self::assertNull($query->getFilter('test1'));
    }

    public function testShouldRunWhenLookup(): void
    {
        $query = new ListQuery();
        $filter = new FilterField('name', 'value1');
        $query->addFilter($filter);
        $sortField = new SortField('name', SortField::ASC_ORDER);
        $query->addSort($sortField);

        $mapping = ['name' => 'tag.name'];
        $query->lookup($mapping);

        self::assertEquals('tag.name', $filter->name());
        self::assertEquals('name', $filter->alias());
        self::assertEquals('tag.name', $sortField->name());
        self::assertEquals('name', $sortField->alias());
    }

    public function testShouldNothingWhenMappingEmptyInLookup(): void
    {
        $query = new ListQuery();
        $filter = new FilterField('name', 'value1');
        $query->addFilter($filter);
        $sortField = new SortField('name', SortField::ASC_ORDER);
        $query->addSort($sortField);

        $mapping = [];
        $query->lookup($mapping);

        self::assertEquals('name', $filter->name());
        self::assertEquals('name', $filter->alias());
        self::assertEquals('name', $sortField->name());
        self::assertEquals('name', $sortField->alias());
    }
}
