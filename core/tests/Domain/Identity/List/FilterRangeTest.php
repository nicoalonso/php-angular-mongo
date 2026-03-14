<?php

namespace App\Tests\Domain\Identity\List;

use App\Domain\Identity\List\FilterRange;
use App\Domain\Identity\List\ValueKind;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class FilterRangeTest extends TestCase
{
    public function testShouldRunWhenCreateEmpty(): void
    {
        $range = new FilterRange();

        self::assertFalse($range->hasFrom());
        self::assertFalse($range->hasTo());
        self::assertFalse($range->hasValue());
        self::assertEquals(ValueKind::STRING, $range->kind());
        self::assertEmpty($range->from());
        self::assertEmpty($range->to());
    }

    public function testShouldHasValueWhenOnlyHasOneValue(): void
    {
        $range = new FilterRange(to: '100');

        self::assertFalse($range->hasFrom());
        self::assertTrue($range->hasTo());
        self::assertTrue($range->hasValue());
        self::assertEquals(ValueKind::STRING, $range->kind());
    }

    public function testShouldEmptyWhenEmptyAndParseAsDate(): void
    {
        $range = new FilterRange();
        $range->parse(ValueKind::DATE);

        self::assertFalse($range->hasFrom());
        self::assertFalse($range->hasTo());
        self::assertFalse($range->hasValue());
        self::assertEquals(ValueKind::DATE, $range->kind());
        self::assertEmpty($range->from());
        self::assertEmpty($range->to());
    }

    public function testShouldRunWhenCreate(): void
    {
        $range = new FilterRange('10', '50');

        self::assertTrue($range->hasFrom());
        self::assertTrue($range->hasTo());
        self::assertTrue($range->hasValue());
        self::assertEquals(ValueKind::STRING, $range->kind());
        self::assertEquals('10', $range->from());
        self::assertEquals('50', $range->to());
    }

    public function testShouldRunWhenParseAsInteger(): void
    {
        $range = new FilterRange('10', '50');
        $range->parse(ValueKind::INTEGER);

        self::assertTrue($range->hasFrom());
        self::assertTrue($range->hasTo());
        self::assertTrue($range->hasValue());
        self::assertEquals(ValueKind::INTEGER, $range->kind());
        self::assertSame(10, $range->from());
        self::assertSame(50, $range->to());
    }

    public function testShouldRunWhenParseAsFloat(): void
    {
        $range = new FilterRange('10.5', '50');
        $range->parse(ValueKind::FLOAT);

        self::assertTrue($range->hasFrom());
        self::assertTrue($range->hasTo());
        self::assertEquals(ValueKind::FLOAT, $range->kind());
        self::assertSame(10.5, $range->from());
        self::assertSame(50.0, $range->to());
    }

    public function testShouldRunWhenParseAsDate(): void
    {
        $range = new FilterRange(' 1673352853 ', ' 2023-05-31T00:00:00+02:00 ');
        $range->parse(ValueKind::DATE);

        self::assertTrue($range->hasFrom());
        self::assertTrue($range->hasTo());
        self::assertEquals(ValueKind::DATE, $range->kind());
        self::assertInstanceOf(DateTimeImmutable::class, $range->from());
        self::assertInstanceOf(DateTimeImmutable::class, $range->to());
        self::assertEquals('2023-01-10T12:14:13+00:00', $range->from()->format(DATE_ATOM));
        self::assertEquals('2023-05-31T00:00:00+02:00', $range->to()->format(DATE_ATOM));
    }

    public function testShouldRunWhenParseUsingShortDate(): void
    {
        $range = new FilterRange('2025-02-07', '2025-02-15');
        $range->parse(ValueKind::DATE);

        self::assertTrue($range->hasFrom());
        self::assertTrue($range->hasTo());
        self::assertEquals(ValueKind::DATE, $range->kind());
        self::assertInstanceOf(DateTimeImmutable::class, $range->from());
        self::assertInstanceOf(DateTimeImmutable::class, $range->to());
        self::assertEquals('2025-02-07T00:00:00+01:00', $range->from()->format(DATE_ATOM));
        self::assertEquals('2025-02-16T00:00:00+01:00', $range->to()->format(DATE_ATOM));
    }

    public function testShouldRunWhenParseWrongDate(): void
    {
        $range = new FilterRange('wrong');
        $range->parse(ValueKind::DATE);

        self::assertEquals(ValueKind::DATE, $range->kind());
        self::assertFalse($range->hasFrom());
        self::assertFalse($range->hasTo());
        self::assertFalse($range->hasValue());
    }

    public function testShouldRunWhenModify(): void
    {
        $range = new FilterRange();
        $range->modify(to: '100');

        self::assertFalse($range->hasFrom());
        self::assertTrue($range->hasTo());
        self::assertTrue($range->hasValue());
        self::assertEquals('', $range->from());
        self::assertEquals('100', $range->to());
    }
}
