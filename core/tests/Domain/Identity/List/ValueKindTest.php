<?php

namespace App\Tests\Domain\Identity\List;

use App\Domain\Identity\List\ValueKind;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ValueKindTest extends TestCase
{
    public function testShouldRunWhenToBool(): void
    {
        self::assertTrue(ValueKind::toBool('TRUE'));
        self::assertTrue(ValueKind::toBool('True'));
        self::assertTrue(ValueKind::toBool('true'));
        self::assertTrue(ValueKind::toBool('1'));
        self::assertTrue(ValueKind::toBool('on'));
        self::assertTrue(ValueKind::toBool('yes'));

        self::assertFalse(ValueKind::toBool('false'));
        self::assertFalse(ValueKind::toBool('0'));
        self::assertFalse(ValueKind::toBool('2'));
        self::assertFalse(ValueKind::toBool('off'));
        self::assertFalse(ValueKind::toBool('no'));
        self::assertFalse(ValueKind::toBool('wrong'));
    }

    public function testShouldRunWhenToDate(): void
    {
        self::assertNull(ValueKind::toDate(''));
        self::assertNull(ValueKind::toDate('wrong'));

        self::assertInstanceOf(DateTimeImmutable::class, ValueKind::toDate('2021-01-01'));
        self::assertInstanceOf(DateTimeImmutable::class, ValueKind::toDate('2023-05-31T00:00:00+02:00'));
        self::assertInstanceOf(DateTimeImmutable::class, ValueKind::toDate('1673352853'));
    }

    public function testShouldRunWhenCheckShortDate(): void
    {
        self::assertTrue(ValueKind::isShortDate('2021-01-01'));
        self::assertTrue(ValueKind::isShortDate('wrong'));

        self::assertFalse(ValueKind::isShortDate('2023-05-31T00:00:00+02:00'));
        self::assertFalse(ValueKind::isShortDate('1673352853'));
        self::assertFalse(ValueKind::isShortDate(''));
    }

    public function testShouldRunWhenToList(): void
    {
        self::assertSame(['a', 'b', 'c'], ValueKind::toList('a,b,c'));
        self::assertSame(['a', 'b', 'c'], ValueKind::toList('a;b;c'));
        self::assertSame(['a', 'b', 'c'], ValueKind::toList('a,b;c'));
        self::assertSame(['a', 'b', 'c'], ValueKind::toList('a b c'));
        self::assertSame(['a', 'b', 'c'], ValueKind::toList('a,b c'));
    }
}
