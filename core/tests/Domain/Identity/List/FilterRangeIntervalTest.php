<?php

namespace App\Tests\Domain\Identity\List;

use App\Domain\Identity\List\FilterRangeInterval;
use PHPUnit\Framework\TestCase;

class FilterRangeIntervalTest extends TestCase
{
    public function testShouldFalseWhenWhenCheckFieldName(): void
    {
        list ($name, $interval) = FilterRangeInterval::check('name');

        self::assertEquals('name', $name);
        self::assertEquals(FilterRangeInterval::NONE, $interval);
    }

    public function testShouldFromWhenCheckFieldName(): void
    {
        list ($name, $interval) = FilterRangeInterval::check('fromDate');

        self::assertEquals('date', $name);
        self::assertEquals(FilterRangeInterval::FROM, $interval);
    }

    public function testShouldToWhenCheckFieldName(): void
    {
        list ($name, $interval) = FilterRangeInterval::check('toDate');

        self::assertEquals('date', $name);
        self::assertEquals(FilterRangeInterval::TO, $interval);
    }
}
