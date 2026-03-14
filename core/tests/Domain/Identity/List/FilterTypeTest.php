<?php

namespace App\Tests\Domain\Identity\List;

use App\Domain\Identity\List\FilterType;
use PHPUnit\Framework\TestCase;

class FilterTypeTest extends TestCase
{
    public function testShouldRunWhenCheckList(): void
    {
        self::assertTrue(FilterType::IN->isList());
        self::assertTrue(FilterType::ALL->isList());

        self::assertFalse(FilterType::WILDCARD->isList());
        self::assertFalse(FilterType::MATCH->isList());
        self::assertFalse(FilterType::FUZZY->isList());
        self::assertFalse(FilterType::RANGE->isList());
        self::assertFalse(FilterType::EXISTS->isList());
    }
}
