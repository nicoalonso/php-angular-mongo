<?php

namespace App\Tests\Domain\Identity\List;

use App\Domain\Identity\List\Exception\InvalidFilterMapException;
use App\Domain\Identity\List\FieldMap;
use App\Domain\Identity\List\FieldOption;
use App\Domain\Identity\List\FilterType;
use App\Domain\Identity\List\ValueKind;
use PHPUnit\Framework\TestCase;

class FieldMapTest extends TestCase
{
    public function testShouldFailWhenEmptyMap(): void
    {
        $this->expectException(InvalidFilterMapException::class);
        new FieldMap(0, '');
    }

    public function testShouldRunWhenCreate(): void
    {
        $fieldMap = new FieldMap(1, 'id');

        self::assertEquals('id', $fieldMap->alias());
        self::assertEquals('id', $fieldMap->fieldName());
        self::assertEquals(FilterType::WILDCARD, $fieldMap->type());
        self::assertEquals(ValueKind::STRING, $fieldMap->kind());
        self::assertTrue($fieldMap->options()->canSelect());
        self::assertTrue($fieldMap->canSelect());
        self::assertTrue($fieldMap->canFilter());
        self::assertTrue($fieldMap->canSort());
        self::assertFalse($fieldMap->canExclude());
        self::assertFalse($fieldMap->canJoin());
    }

    public function testShouldRunWhenDefinedAsAlias(): void
    {
        $fieldMap = new FieldMap('_id', 'id');

        self::assertEquals('_id', $fieldMap->alias());
        self::assertEquals('id', $fieldMap->fieldName());
        self::assertEquals(FilterType::WILDCARD, $fieldMap->type());
        self::assertTrue($fieldMap->options()->canSelect());
        self::assertTrue($fieldMap->canSelect());
        self::assertTrue($fieldMap->canFilter());
        self::assertTrue($fieldMap->canSort());
        self::assertFalse($fieldMap->canExclude());
        self::assertFalse($fieldMap->canJoin());
    }

    public function testShouldRunWhenAliasAndType(): void
    {
        $fieldMap = new FieldMap('id', FilterType::MATCH);

        self::assertEquals('id', $fieldMap->alias());
        self::assertEquals('id', $fieldMap->fieldName());
        self::assertEquals(FilterType::MATCH, $fieldMap->type());
        self::assertTrue($fieldMap->options()->canSelect());
        self::assertTrue($fieldMap->canSelect());
        self::assertTrue($fieldMap->canFilter());
        self::assertTrue($fieldMap->canSort());
        self::assertFalse($fieldMap->canExclude());
        self::assertFalse($fieldMap->canJoin());
    }

    public function testShouldRunWhenAliasAndKind(): void
    {
        $fieldMap = new FieldMap('id', ValueKind::INTEGER);

        self::assertEquals('id', $fieldMap->alias());
        self::assertEquals('id', $fieldMap->fieldName());
        self::assertEquals(FilterType::WILDCARD, $fieldMap->type());
        self::assertEquals(ValueKind::INTEGER, $fieldMap->kind());
        self::assertTrue($fieldMap->options()->canSelect());
        self::assertTrue($fieldMap->canSelect());
        self::assertTrue($fieldMap->canFilter());
        self::assertTrue($fieldMap->canSort());
        self::assertFalse($fieldMap->canExclude());
        self::assertFalse($fieldMap->canJoin());
    }

    public function testShouldRunWhenAliasAndTypeAndNotSelectFilter(): void
    {
        $fieldMap = new FieldMap('id', [FilterType::MATCH, FieldOption::NO_SELECT, FieldOption::NO_FILTER]);

        self::assertEquals('id', $fieldMap->alias());
        self::assertEquals('id', $fieldMap->fieldName());
        self::assertEquals(FilterType::MATCH, $fieldMap->type());
        self::assertFalse($fieldMap->canSelect());
        self::assertFalse($fieldMap->canFilter());
        self::assertTrue($fieldMap->canSort());
        self::assertFalse($fieldMap->canExclude());
        self::assertFalse($fieldMap->canJoin());
    }

    public function testShouldRunWhenExcludeOption(): void
    {
        $fieldMap = new FieldMap('id', FieldOption::EXCLUDE);

        self::assertEquals('id', $fieldMap->alias());
        self::assertEquals('id', $fieldMap->fieldName());
        self::assertEquals(FilterType::WILDCARD, $fieldMap->type());
        self::assertTrue($fieldMap->canSelect());
        self::assertTrue($fieldMap->canFilter());
        self::assertTrue($fieldMap->canSort());
        self::assertTrue($fieldMap->canExclude());
        self::assertFalse($fieldMap->canJoin());
    }

    public function testShouldRunWhenAliasAndTypeAndNotSelectFilterWithAlias(): void
    {
        $mapping = ['id', FilterType::MATCH, FieldOption::NO_SELECT, FieldOption::NO_FILTER];
        $fieldMap = new FieldMap('_id', $mapping);

        self::assertEquals('_id', $fieldMap->alias());
        self::assertEquals('id', $fieldMap->fieldName());
        self::assertEquals(FilterType::MATCH, $fieldMap->type());
        self::assertFalse($fieldMap->canSelect());
        self::assertFalse($fieldMap->canFilter());
        self::assertTrue($fieldMap->canSort());
        self::assertFalse($fieldMap->canExclude());
        self::assertFalse($fieldMap->canJoin());
    }

    public function testShouldRunWhenCanJoin(): void
    {
        $fieldMap = new FieldMap('_id', ['id', FieldOption::NO_FILTER, FieldOption::JOIN]);

        self::assertEquals('_id', $fieldMap->alias());
        self::assertEquals('id', $fieldMap->fieldName());
        self::assertEquals(FilterType::WILDCARD, $fieldMap->type());
        self::assertTrue($fieldMap->canSelect());
        self::assertFalse($fieldMap->canFilter());
        self::assertTrue($fieldMap->canSort());
        self::assertFalse($fieldMap->canExclude());
        self::assertTrue($fieldMap->canJoin());
    }
}
