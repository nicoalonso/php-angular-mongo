<?php

namespace App\Tests\Domain\Identity\List;

use App\Domain\Identity\List\FieldMap;
use App\Domain\Identity\List\FilterField;
use App\Domain\Identity\List\FilterRange;
use App\Domain\Identity\List\FilterRangeInterval;
use App\Domain\Identity\List\FilterType;
use App\Domain\Identity\List\FilterVisitor;
use App\Domain\Identity\List\ValueKind;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class DummyFilterVisitant implements FilterVisitor
{
    public bool $visited = false;

    public function visit(FilterField $field, mixed $builder): bool
    {
        $this->visited = true;
        return true;
    }
}

class FilterFieldTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $filter = new FilterField('test', 'value1');

        self::assertEquals('test', $filter->alias());
        self::assertEquals('test', $filter->name());
        self::assertEquals('value1', $filter->input());
        self::assertEquals('value1', $filter->value());
        self::assertTrue($filter->hasValue());
        self::assertEquals(FilterType::WILDCARD, $filter->type());
        self::assertEquals(ValueKind::STRING, $filter->kind());
        self::assertFalse($filter->isRange());
        self::assertFalse($filter->isList());
        self::assertTrue($filter->is('test'));
        self::assertFalse($filter->is('test2'));
        self::assertFalse($filter->hasVisitant());
    }

    public function testShouldValueWhenCreate(): void
    {
        $filter = new FilterField(
            'test',
            'true',
            FilterType::WILDCARD,
            ValueKind::BOOLEAN,
            true
        );

        self::assertEquals('test', $filter->alias());
        self::assertEquals('test', $filter->name());
        self::assertEquals('true', $filter->input());
        self::assertTrue($filter->value());
        self::assertTrue($filter->hasValue());
        self::assertEquals(FilterType::WILDCARD, $filter->type());
        self::assertEquals(ValueKind::BOOLEAN, $filter->kind());
    }

    public function testShouldRunWhenChangeValue(): void
    {
        $filter = new FilterField('test', '2025-01-01');
        $filter->changeValue(new DateTimeImmutable('2025-01-02'), FilterType::MATCH,ValueKind::DATE);

        self::assertEquals('test', $filter->alias());
        self::assertEquals('test', $filter->name());
        self::assertEquals('2025-01-01', $filter->input());
        self::assertInstanceOf(DateTimeImmutable::class, $filter->value());
        self::assertEquals(FilterType::MATCH, $filter->type());
        self::assertEquals(ValueKind::DATE, $filter->kind());
    }

    public function testShouldNoneWhenIntervalIsNone(): void
    {
        $filter = new FilterField('test', '1234');
        $filter->range(FilterRangeInterval::NONE, '1234');

        self::assertNotInstanceOf(FilterRange::class, $filter->value());
    }

    public function testShouldMakeWhenRangeTo(): void
    {
        $filter = new FilterField('test', '1234', FilterType::RANGE);
        $filter->range(FilterRangeInterval::TO, '1234');

        self::assertInstanceOf(FilterRange::class, $filter->value());
        self::assertTrue($filter->isRange());
        self::assertTrue($filter->hasValue());
        self::assertFalse($filter->value()->hasFrom());
        self::assertTrue($filter->value()->hasTo());
    }

    public function testShouldMakeWhenRangeEmptyTo(): void
    {
        $filter = new FilterField('test', '', FilterType::RANGE);
        $filter->range(FilterRangeInterval::TO, '');

        self::assertInstanceOf(FilterRange::class, $filter->value());
        self::assertTrue($filter->isRange());
        self::assertFalse($filter->hasValue());
        self::assertFalse($filter->value()->hasValue());
    }

    public function testShouldMakeWhenRangeFrom(): void
    {
        $filter = new FilterField('test', '1234', FilterType::RANGE);
        $filter->range(FilterRangeInterval::FROM, '1234');

        self::assertInstanceOf(FilterRange::class, $filter->value());
        self::assertTrue($filter->value()->hasFrom());
        self::assertFalse($filter->value()->hasTo());
    }

    public function testShouldUpdateWhenRangeTo(): void
    {
        $range = new FilterRange(from: '1');
        $filter = new FilterField(
            'test',
            '1234',
            FilterType::RANGE,
            ValueKind::STRING,
            $range,
        );
        $filter->range(FilterRangeInterval::TO, '1234');

        self::assertInstanceOf(FilterRange::class, $filter->value());
        self::assertTrue($filter->value()->hasFrom());
        self::assertTrue($filter->value()->hasTo());
    }

    public function testShouldUpdateWhenRangeFrom(): void
    {
        $range = new FilterRange(to: '9999');
        $filter = new FilterField(
            'test',
            '1234',
            FilterType::RANGE,
            ValueKind::STRING,
            $range,
        );
        $filter->range(FilterRangeInterval::FROM, '1234');

        self::assertInstanceOf(FilterRange::class, $filter->value());
        self::assertTrue($filter->value()->hasFrom());
        self::assertTrue($filter->value()->hasTo());
    }

    public function testShouldRunWhenSetVisitant(): void
    {
        $filter = new FilterField('test', 'value1');
        $visitor = new DummyFilterVisitant();
        $filter->setVisitant($visitor);

        self::assertTrue($filter->hasVisitant());
        self::assertTrue($filter->visit(''));
        self::assertTrue($visitor->visited);
    }

    public function testShouldRunWhenMappedAsBoolean(): void
    {
        $filter = new FilterField('test', 'TRUE');
        $fieldMap = new FieldMap('dummy', [FilterType::MATCH, ValueKind::BOOLEAN]);
        $filter->mapping($fieldMap);

        self::assertEquals(FilterType::MATCH, $filter->type());
        self::assertEquals(ValueKind::BOOLEAN, $filter->kind());
        self::assertTrue($filter->hasValue());
        self::assertTrue($filter->value());
    }

    public function testShouldRunWhenMappedAsBooleanFalse(): void
    {
        $filter = new FilterField('test', 'false');
        $fieldMap = new FieldMap('dummy', [FilterType::MATCH, ValueKind::BOOLEAN]);
        $filter->mapping($fieldMap);

        self::assertEquals(FilterType::MATCH, $filter->type());
        self::assertEquals(ValueKind::BOOLEAN, $filter->kind());
        self::assertTrue($filter->hasValue());
        self::assertFalse($filter->value());
    }

    public function testShouldRunWhenMappedAsBooleanWrongString(): void
    {
        $filter = new FilterField('test', 'wrong');
        $fieldMap = new FieldMap('dummy', ValueKind::BOOLEAN);
        $filter->mapping($fieldMap);

        self::assertEquals(ValueKind::BOOLEAN, $filter->kind());
        self::assertTrue($filter->hasValue());
        self::assertFalse($filter->value());
    }

    public function testShouldRunWhenMappedAsInteger(): void
    {
        $filter = new FilterField('test', '1234');
        $fieldMap = new FieldMap('dummy', [ValueKind::INTEGER]);
        $filter->mapping($fieldMap);

        self::assertEquals(ValueKind::INTEGER, $filter->kind());
        self::assertTrue($filter->hasValue());
        self::assertSame(1234, $filter->value());
    }

    public function testShouldRunWhenMappedAsZeroInteger(): void
    {
        $filter = new FilterField('test', '0');
        $fieldMap = new FieldMap('dummy', [ValueKind::INTEGER]);
        $filter->mapping($fieldMap);

        self::assertEquals(ValueKind::INTEGER, $filter->kind());
        self::assertTrue($filter->hasValue());
        self::assertSame(0, $filter->value());
    }

    public function testShouldRunWhenMappedAsDate(): void
    {
        $filter = new FilterField('test', '2025-01-01');
        $fieldMap = new FieldMap('dummy', ValueKind::DATE);
        $filter->mapping($fieldMap);

        self::assertEquals('test', $filter->alias());
        self::assertEquals('dummy', $filter->name());
        self::assertEquals('2025-01-01', $filter->input());
        self::assertEquals(ValueKind::DATE, $filter->kind());
        self::assertInstanceOf(DateTimeImmutable::class, $filter->value());
        self::assertEquals('2025-01-01T00:00:00+01:00', $filter->value()->format(DATE_ATOM));
    }

    public function testShouldRunWhenMappedAsRangeDate(): void
    {
        $filter = new FilterField('test', '2025-01-01');
        $fieldMap = new FieldMap('dummy', [FilterType::RANGE, ValueKind::DATE]);
        $filter->mapping($fieldMap);

        self::assertEquals('test', $filter->alias());
        self::assertEquals('dummy', $filter->name());
        self::assertEquals('2025-01-01', $filter->input());
        self::assertEquals(ValueKind::DATE, $filter->kind());
        self::assertInstanceOf(FilterRange::class, $filter->value());
        self::assertInstanceOf(DateTimeImmutable::class, $filter->value()->from());
        self::assertEquals('2025-01-01T00:00:00+01:00', $filter->value()->from()->format(DATE_ATOM));
    }

    public function testShouldRunWhenMappedAsDateAndHasRange(): void
    {
        $filter = new FilterField('test', '2025-01-01', FilterType::RANGE);
        $filter->range(FilterRangeInterval::FROM, '2025-01-01');
        $filter->range(FilterRangeInterval::TO, '2025-01-31');

        $fieldMap = new FieldMap('dummy', [FilterType::RANGE, ValueKind::DATE]);
        $filter->mapping($fieldMap);

        self::assertEquals('test', $filter->alias());
        self::assertEquals('dummy', $filter->name());
        self::assertEquals('2025-01-01', $filter->input());
        self::assertEquals(FilterType::RANGE, $filter->type());
        self::assertEquals(ValueKind::DATE, $filter->kind());
        self::assertInstanceOf(FilterRange::class, $filter->value());
        self::assertInstanceOf(DateTimeImmutable::class, $filter->value()->from());
        self::assertInstanceOf(DateTimeImmutable::class, $filter->value()->to());
        self::assertEquals('2025-01-01T00:00:00+01:00', $filter->value()->from()->format(DATE_ATOM));
        self::assertEquals('2025-02-01T00:00:00+01:00', $filter->value()->to()->format(DATE_ATOM));
    }

    public function testShouldRunWhenMappedAsList(): void
    {
        $filter = new FilterField('test', 'value1,value2');
        $fieldMap = new FieldMap('dummy', FilterType::IN);

        $filter->mapping($fieldMap);

        $list = $filter->value();
        self::assertCount(2, $list);
        self::assertSame(['value1', 'value2'], $list);
    }

    public function testShouldRunWhenMappedAsIntegerList(): void
    {
        $filter = new FilterField('test', '1,2');
        $fieldMap = new FieldMap('dummy', [FilterType::ALL, ValueKind::INTEGER]);

        $filter->mapping($fieldMap);

        $list = $filter->value();
        self::assertCount(2, $list);
        self::assertSame([1, 2], $list);
    }
}
