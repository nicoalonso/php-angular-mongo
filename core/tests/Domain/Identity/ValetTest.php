<?php

namespace App\Tests\Domain\Identity;

use App\Domain\Identity\Valet;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ValetTest extends TestCase
{
    public function testShouldRunWhenIsInsensitiveCase(): void
    {
        $data = ['TeSt' => 'Dummy Example'];
        $valet = new Valet($data);
        $valet->add('Dummy', 1);

        $dataExpected = ['TeSt' => 'Dummy Example', 'Dummy' => 1];
        self::assertEquals($dataExpected, $valet->getData());
        self::assertEquals('Dummy Example', $valet->get('test'));
        self::assertEquals('Dummy Example', $valet->get('TEST'));
        self::assertEquals('Dummy Example', $valet->get('TeSt'));
        self::assertEquals('Dummy Example', $valet->get('tEsT'));
        self::assertTrue($valet->has('test'));
        self::assertTrue($valet->has('TEST'));
        self::assertTrue($valet->has('tEsT'));
        self::assertTrue($valet->has('TeSt'));
        self::assertEquals(1, $valet->toInt('dummy'));
    }

    public function testShouldNullWhenKeyNotExists(): void
    {
        $data = ['test' => 'Dummy Example'];
        $valet = new Valet($data);

        self::assertNull($valet->get('dummy'));
    }

    public function testShouldValueWhenKeyAlreadyExists(): void
    {
        $data = ['test' => 'Dummy Example'];
        $valet = new Valet($data);

        self::assertEquals('Dummy Example', $valet->get('test'));
    }

    public function testShouldRunWhenGetMultipleKeys(): void
    {
        $data = ['test' => 'Dummy Example'];
        $valet = new Valet($data);
        $value = $valet->get(['test1', 'test', 'test2']);

        self::assertEquals('Dummy Example', $value);
    }

    public function testShouldGetFirstValueWhenMultipleKeys(): void
    {
        $data = [
            'test' => 'Dummy Example',
            'test2' => 'Dummy Example 2',
        ];
        $valet = new Valet($data);
        $value = $valet->get(['test1', 'test', 'test2']);

        self::assertEquals('Dummy Example', $value);
    }

    public function testShouldDefaultValueWhenKeyNotExists(): void
    {
        $data = ['test' => 'Dummy Example'];
        $valet = new Valet($data);

        self::assertSame(1, $valet->get('dummy', 1));
    }

    public function testShouldEmptyStringWhenKeyNotExistsOnToString(): void
    {
        $data = ['test' => 'Dummy Example'];
        $valet = new Valet($data);

        self::assertSame('', $valet->toString('dummy'));
    }

    public function testShouldEmptyNullWhenKeyNotExistsAndNullIsDefaultValueOnToString(): void
    {
        $data = ['test' => 'Dummy Example'];
        $valet = new Valet($data);

        self::assertNull($valet->toString('dummy', null));
    }

    public function testShouldStringWhenKeyExistsOnToString(): void
    {
        $data = ['test' => 'Dummy Example'];
        $valet = new Valet($data);

        self::assertSame('Dummy Example', $valet->toString('test', null));
    }

    public function testShouldStringWhenKeyExistsOnToStringAndMultipleKeys(): void
    {
        $data = ['test' => 'Dummy Example'];
        $valet = new Valet($data);
        $value = $valet->toString(['test1', 'test2', 'test'], null);

        self::assertSame('Dummy Example', $value);
    }

    public function testShouldStringWhenKeyExistsAndValueIsANumberOnToString(): void
    {
        $data = ['test' => 12456];
        $valet = new Valet($data);

        self::assertSame('12456', $valet->toString('test', null));
    }

    public function testShouldZeroWhenKeyNotExistsOnToInt(): void
    {
        $data = ['test' => 12456];
        $valet = new Valet($data);

        self::assertSame(0, $valet->toInt('dummy'));
    }

    public function testShouldNumberWhenKeyExistsOnToInt(): void
    {
        $data = ['test' => 12456];
        $valet = new Valet($data);

        self::assertSame(12456, $valet->toInt('test'));
    }

    public function testShouldNullWhenKeyNotExistsAndNullAsDefaultOnToInt(): void
    {
        $data = ['test' => 12456];
        $valet = new Valet($data);

        self::assertNull($valet->toInt('dummy', null));
    }

    public function testShouldNumberWhenKeyExistsAndIsStringOnToInt(): void
    {
        $data = ['test' => '12456'];
        $valet = new Valet($data);

        self::assertSame(12456, $valet->toInt('test'));
    }

    public function testShouldZeroWhenKeyNotExistsOnToFloat(): void
    {
        $data = ['test' => '12456'];
        $valet = new Valet($data);

        self::assertSame(0.0, $valet->toFloat('dummy'));
    }

    public function testShouldNullWhenKeyNotExistsAndNullAsDefaultOnToFloat(): void
    {
        $data = ['test' => '12456'];
        $valet = new Valet($data);

        self::assertNull($valet->toFloat('dummy', null));
    }

    public function testShouldFloatWhenKeyExistsAndIsStringOnToFloat(): void
    {
        $data = ['test' => '12456'];
        $valet = new Valet($data);

        self::assertSame(12456.0, $valet->toFloat('test'));
    }

    public function testShouldFloatWhenKeyExistsOnToFloat(): void
    {
        $data = ['test' => 12456.5];
        $valet = new Valet($data);

        self::assertSame(12456.5, $valet->toFloat('test'));
    }

    public function testShouldFalseWhenWhenKeyNotExistsOnToBool(): void
    {
        $data = ['test' => 'dummy'];
        $valet = new Valet($data);

        self::assertFalse($valet->toBool('dummy'));
    }

    public function testShouldNullWhenKeyNotExistAndNullAsDefaultValueOnToBool(): void
    {
        $data = ['test' => 'dummy'];
        $valet = new Valet($data);

        self::assertNull($valet->toBool('dummy', null));
    }

    public function testShouldFalseWhenKeyExistsOnToBool(): void
    {
        $data = ['test' => '0'];
        $valet = new Valet($data);

        self::assertFalse($valet->toBool('test'));
    }

    public function testShouldTrueWhenKeyExistsOnToBool(): void
    {
        $data = ['test' => '1'];
        $valet = new Valet($data);

        self::assertTrue($valet->toBool('test'));
    }

    public function testShouldTrueWhenKeyExistsOnToBoolAsString(): void
    {
        $data = ['test' => 'true'];
        $valet = new Valet($data);

        self::assertTrue($valet->toBool('test'));
    }

    public function testShouldTrueWhenKeyExistsAndValueIsBooleanOnToBool(): void
    {
        $data = ['test' => true];
        $valet = new Valet($data);

        self::assertTrue($valet->toBool('test'));
    }

    public function testShouldDefaultArrayWhenKeyNotExistToArray(): void
    {
        $data = ['test' => ['dummy' => 1]];
        $valet = new Valet($data);

        self::assertEmpty($valet->toArray('dummy'));
    }

    public function testShouldArrayWhenKeyExistToArray(): void
    {
        $data = ['test' => ['dummy' => 1]];
        $valet = new Valet($data);

        self::assertEquals(['dummy' => 1], $valet->toArray('test'));
    }

    public function testShouldStringArrayWhenKeyExistToArray(): void
    {
        $data = ['test' => ['dummy' => 1]];
        $valet = new Valet($data);
        $list = $valet->toStringArray('test');

        self::assertSame(['1'], $list);
    }

    public function testShouldAssocArrayWhenToAssoc(): void
    {
        $data = [
            'test' => [
                'test',
                'dummy' => 'path',
                1,
                new DateTimeImmutable(),
            ],
        ];
        $valet = new Valet($data);
        $list = $valet->toAssocArray('test');

        self::assertSame(['dummy' => 'path'], $list);
    }

    public function testShouldObjectWhenToTransform(): void
    {
        $data = ['test' => ['dummy' => 1]];
        $valet1 = new Valet($data);
        $valet2 = $valet1->toValet('test');

        self::assertInstanceOf(Valet::class, $valet2);
        self::assertTrue($valet2->has('dummy'));
    }

    public function testShouldTransformArrayWhenToList(): void
    {
        $data = ['test' => [['dummy' => 1], ['dummy' => 2]]];
        $valet = new Valet($data);
        $list = $valet->toList('test');

        self::assertCount(2, $list);
        self::assertInstanceOf(Valet::class, $list[0]);
        self::assertInstanceOf(Valet::class, $list[1]);
        self::assertTrue($list[0]->has('dummy'));
    }

    public function testShouldFalseWhenNotFoundOnIsString(): void
    {
        $data = ['test' => [['dummy' => 1], ['dummy' => 2]]];
        $valet = new Valet($data);
        $result = $valet->isString('test2');

        self::assertFalse($result);
    }

    public function testShouldFalseWhenIsArrayOnIsString(): void
    {
        $data = ['test' => [['dummy' => 1], ['dummy' => 2]]];
        $valet = new Valet($data);
        $result = $valet->isString('test');

        self::assertFalse($result);
    }

    public function testShouldFalseWhenIsNumberOnIsString(): void
    {
        $data = ['test' => ['dummy' => 1], 'dummy' => 2];
        $valet = new Valet($data);
        $result = $valet->isString('dummy');

        self::assertFalse($result);
    }

    public function testShouldTrueWhenOnIsString(): void
    {
        $data = ['test' => 'valid', 'dummy' => 2];
        $valet = new Valet($data);
        $result = $valet->isString('test');

        self::assertTrue($result);
    }

    public function testShouldFalseWhenNotFoundOnIsArray(): void
    {
        $data = ['test' => [['dummy' => 1], ['dummy' => 2]]];
        $valet = new Valet($data);
        $result = $valet->isArray('test2');

        self::assertFalse($result);
    }

    public function testShouldFalseWhenIsStringOnIsArray(): void
    {
        $data = ['test' => [['dummy' => 1], ['dummy' => 2]], 'test2' => 'xxx'];
        $valet = new Valet($data);
        $result = $valet->isArray('test2');

        self::assertFalse($result);
    }

    public function testShouldFalseWhenIsNumberOnIsArray(): void
    {
        $data = ['test' => [['dummy' => 1], ['dummy' => 2]], 'test2' => 1];
        $valet = new Valet($data);
        $result = $valet->isArray('test2');

        self::assertFalse($result);
    }

    public function testShouldTrueWhenIsArray(): void
    {
        $data = ['test' => [['dummy' => 1], ['dummy' => 2]], 'test2' => 1];
        $valet = new Valet($data);
        $result = $valet->isArray('test');

        self::assertTrue($result);
    }

    public function testShouldNullWhenNullableOnToDate(): void
    {
        $input = [];
        $data = new Valet($input);

        self::assertNull($data->toDate('test'));
        self::assertNull($data->toDateImmutable('test'));
    }

    public function testShouldNotNullWhenNullableOnToDate(): void
    {
        $input = [];
        $data = new Valet($input);

        self::assertNotNull($data->toDate('test', nullable: false));
    }

    public function testShouldRunWhenIntegerOnToDate(): void
    {
        $input = ['createdAt' => 1630000000];
        $data = new Valet($input);

        $date = $data->toDate('createdAt');
        $dateImmutable = $data->toDateImmutable('createdAt');

        self::assertInstanceOf(DateTime::class, $date);
        self::assertInstanceOf(DateTimeImmutable::class, $dateImmutable);
        self::assertEquals('2021-08-26T17:46:40+00:00', $date->format('c'));
    }

    public function testShouldRunWhenStringOnToDate(): void
    {
        $input = ['createdAt' => '2024-08-01T09:40:22+02:00'];
        $data = new Valet($input);

        $date = $data->toDate('createdAt');
        $dateImmutable = $data->toDateImmutable('createdAt');

        self::assertInstanceOf(DateTime::class, $date);
        self::assertInstanceOf(DateTimeImmutable::class, $dateImmutable);
        self::assertEquals('2024-08-01T09:40:22+02:00', $date->format('c'));
    }

    public function testShouldRunWhenISO8601UOnToDate(): void
    {
        $input = ['createdAt' => '2024-08-01T08:18:41.669Z'];
        $data = new Valet($input);

        $date = $data->toDate('createdAt', DATE_ISO8601U);
        $dateImmutable = $data->toDateImmutable('createdAt', DATE_ISO8601U);

        self::assertInstanceOf(DateTime::class, $date);
        self::assertInstanceOf(DateTimeImmutable::class, $dateImmutable);
        self::assertEquals('2024-08-01T08:18:41+00:00', $date->format('c'));
    }

    public function testShouldUseModifierWhenShortDateOnToDate(): void
    {
        $input = ['createdAt' => '2024-08-01'];
        $data = new Valet($input);

        $dateMidnight = $data->toDateImmutable('createdAt', modifier: 'midnight');
        $dateTomorrow = $data->toDateImmutable('createdAt', modifier: 'tomorrow');

        self::assertEquals('2024-08-01T00:00:00+02:00', $dateMidnight->format(DATE_ATOM));
        self::assertEquals('2024-08-02T00:00:00+02:00', $dateTomorrow->format(DATE_ATOM));
    }

    public function testShouldIgnoreModifierWhenFullDateOnToDate(): void
    {
        $input = ['createdAt' => '2024-08-01T08:18:41+02:00'];
        $data = new Valet($input);

        $dateMidnight = $data->toDateImmutable('createdAt', modifier: 'midnight');
        $dateTomorrow = $data->toDateImmutable('createdAt', modifier: 'tomorrow');

        self::assertEquals('2024-08-01T08:18:41+02:00', $dateMidnight->format(DATE_ATOM));
        self::assertEquals('2024-08-01T08:18:41+02:00', $dateTomorrow->format(DATE_ATOM));
    }
}
