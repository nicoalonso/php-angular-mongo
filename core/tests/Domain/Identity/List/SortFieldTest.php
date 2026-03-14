<?php

namespace App\Tests\Domain\Identity\List;

use App\Domain\Identity\List\FieldMap;
use App\Domain\Identity\List\SortField;
use PHPUnit\Framework\TestCase;

class SortFieldTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $sort = new SortField('test');

        self::assertEquals('test', $sort->alias());
        self::assertEquals('test', $sort->name());
        self::assertEquals('asc', $sort->direction());
    }

    public function testShouldValidWhenCreateFromStringWithoutDirection(): void
    {
        $sort = SortField::fromString('test');

        self::assertEquals('test', $sort->alias());
        self::assertEquals('test', $sort->name());
        self::assertEquals('asc', $sort->direction());
    }

    public function testShouldAscWhenCreateFromStringWithAscDirection(): void
    {
        $sort = SortField::fromString('+test');

        self::assertEquals('test', $sort->alias());
        self::assertEquals('test', $sort->name());
        self::assertEquals('asc', $sort->direction());
    }

    public function testShouldDescWhenCreateFromStringWithDescDirection(): void
    {
        $sort = SortField::fromString('-test');

        self::assertEquals('test', $sort->alias());
        self::assertEquals('test', $sort->name());
        self::assertEquals('desc', $sort->direction());
    }

    public function testShouldUpdateNameWhenUpdateByMap(): void
    {
        $sort = SortField::fromString('-test');
        $fieldMap = new FieldMap('test', 'dummy');
        $sort->mapping($fieldMap);

        self::assertEquals('test', $sort->alias());
        self::assertEquals('dummy', $sort->name());
        self::assertEquals('desc', $sort->direction());
    }
}
