<?php

namespace App\Tests\Domain\Identity\List;

use App\Domain\Identity\List\Field;
use App\Domain\Identity\List\FieldMapList;
use App\Domain\Identity\List\FieldOption;
use App\Domain\Identity\List\FieldOptions;
use App\Domain\Identity\List\FilterField;
use App\Domain\Identity\List\FilterRange;
use App\Domain\Identity\List\FilterType;
use App\Domain\Identity\List\SortField;
use App\Domain\Identity\List\ValueKind;
use PHPUnit\Framework\TestCase;

class FieldMapListTest extends TestCase
{
    public function testShouldNotCanSelectWhenAliasNotFound(): void
    {
        $fieldMap = ['name'];
        $map = new FieldMapList($fieldMap);
        $field = new Field('test');

        self::assertFalse($map->canSelect($field));
        self::assertEquals('test', $field->alias());
        self::assertEquals('test', $field->name());
        self::assertEmpty($map->getJoins());
    }

    public function testShouldNotCanSelectWhenAliasFoundAndDisableSelect(): void
    {
        $fieldMap = ['alias' => FieldOption::NO_SELECT];
        $map = new FieldMapList($fieldMap);
        $field = new Field('alias');

        self::assertFalse($map->canSelect($field));
        self::assertEquals('alias', $field->alias());
        self::assertEquals('alias', $field->name());
        self::assertTrue($map->hasField('alias'));
    }

    public function testShouldCanSelectAndUpdateFieldNameWhenAliasFound(): void
    {
        $fieldMap = ['alias' => 'fieldName'];
        $map = new FieldMapList($fieldMap);
        $field = new Field('alias');

        self::assertTrue($map->canSelect($field));
        self::assertEquals('alias', $field->alias());
        self::assertEquals('fieldName', $field->name());
    }

    public function testShouldNotCanFilterWhenAliasNotFound(): void
    {
        $fieldMap = ['name'];
        $map = new FieldMapList($fieldMap);
        $filter = new FilterField('test', 'value1');

        self::assertFalse($map->canFilter($filter));
        self::assertEquals('test', $filter->alias());
        self::assertEquals('test', $filter->name());
        self::assertEquals('value1', $filter->value());
        self::assertEquals(FilterType::WILDCARD, $filter->type());
    }

    public function testShouldNotCanFilterWhenAliasFoundAndDisableFilter(): void
    {
        $fieldMap = ['alias' => FieldOption::NO_FILTER];
        $map = new FieldMapList($fieldMap);
        $filter = new FilterField('test', 'value1');

        self::assertFalse($map->canFilter($filter));
        self::assertEquals('test', $filter->alias());
        self::assertEquals('test', $filter->name());
        self::assertEquals('value1', $filter->value());
        self::assertEquals(FilterType::WILDCARD, $filter->type());
    }

    public function testShouldCanFilterWhenAliasFound(): void
    {
        $fieldMap = ['alias'];
        $map = new FieldMapList($fieldMap);
        $filter = new FilterField('alias', 'value1');

        self::assertTrue($map->canFilter($filter));
        self::assertEquals('alias', $filter->alias());
        self::assertEquals('alias', $filter->name());
        self::assertEquals('value1', $filter->value());
        self::assertEquals(FilterType::WILDCARD, $filter->type());
    }

    public function testShouldCanFilterAndUpdateFieldNameWhenAliasFound(): void
    {
        $fieldMap = ['alias' => 'fieldName'];
        $map = new FieldMapList($fieldMap);
        $filter = new FilterField('alias', 'value1');

        self::assertTrue($map->canFilter($filter));
        self::assertEquals('alias', $filter->alias());
        self::assertEquals('fieldName', $filter->name());
        self::assertEquals('value1', $filter->value());
        self::assertEquals(FilterType::WILDCARD, $filter->type());
    }

    public function testShouldCanFilterAndUpdateFilterTypeWhenAliasFound(): void
    {
        $fieldMap = ['date' => ['createdAt', FilterType::RANGE, ValueKind::DATE]];
        $map = new FieldMapList($fieldMap);
        $filter = new FilterField('date', '2025-01-01');

        self::assertTrue($map->canFilter($filter));
        self::assertEquals('date', $filter->alias());
        self::assertEquals('createdAt', $filter->name());
        self::assertInstanceOf(FilterRange::class, $filter->value());
        self::assertEquals(FilterType::RANGE, $filter->type());
        self::assertEquals(ValueKind::DATE, $filter->kind());
    }

    public function testShouldCanFilterAndUpdateFiledNameAndFilterTypeWhenAliasFoundWithInverseMap(): void
    {
        $fieldMap = ['date' => [FilterType::RANGE, ValueKind::DATE, 'createdAt']];
        $map = new FieldMapList($fieldMap);
        $filter = new FilterField('date', '2025-01-01');

        self::assertTrue($map->canFilter($filter));
        self::assertEquals('date', $filter->alias());
        self::assertEquals('createdAt', $filter->name());
        self::assertInstanceOf(FilterRange::class, $filter->value());
        self::assertEquals(FilterType::RANGE, $filter->type());
    }

    public function testShouldCanNotSortWhenAliasNotFound(): void
    {
        $fieldMap = ['name'];
        $map = new FieldMapList($fieldMap);
        $sort = new SortField('alias');

        self::assertFalse($map->canSort($sort));
        self::assertEquals('alias', $sort->alias());
        self::assertEquals('alias', $sort->name());
    }

    public function testShouldCanNotSortWhenAliasFoundAndDisableSort(): void
    {
        $fieldMap = ['alias' => FieldOption::NO_SORT];
        $map = new FieldMapList($fieldMap);
        $sort = new SortField('alias');

        self::assertFalse($map->canSort($sort));
        self::assertEquals('alias', $sort->alias());
        self::assertEquals('alias', $sort->name());
    }

    public function testShouldCanSortWhenAliasFound(): void
    {
        $fieldMap = ['alias'];
        $map = new FieldMapList($fieldMap);
        $sort = new SortField('alias');

        self::assertTrue($map->canSort($sort));
        self::assertEquals('alias', $sort->alias());
        self::assertEquals('alias', $sort->name());
    }

    public function testShouldCanSortAndUpdateFieldNameWhenAliasFound(): void
    {
        $fieldMap = ['alias' => 'fieldName'];
        $map = new FieldMapList($fieldMap);
        $sort = new SortField('alias');

        self::assertTrue($map->canSort($sort));
        self::assertEquals('alias', $sort->alias());
        self::assertEquals('fieldName', $sort->name());
    }

    public function testShouldHasJoinsWhenFieldMarkAsJoin(): void
    {
        $fieldMap = ['alias' => ['fieldName', FieldOption::JOIN]];
        $map = new FieldMapList($fieldMap);
        $joins = $map->getJoins();

        self::assertCount(1, $joins);
    }
}
