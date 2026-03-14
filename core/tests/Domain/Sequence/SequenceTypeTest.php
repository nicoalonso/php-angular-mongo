<?php

namespace App\Tests\Domain\Sequence;

use App\Domain\Sequence\SequenceType;
use PHPUnit\Framework\TestCase;

class SequenceTypeTest extends TestCase
{
    public function testShouldRunWhenGetPrefix(): void
    {
        self::assertEquals('SN', SequenceType::MEMBERSHIP->getPrefix());
        self::assertEquals('F-', SequenceType::SALE->getPrefix());
        self::assertEquals('P-', SequenceType::BORROW->getPrefix());
    }
}
