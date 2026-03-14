<?php

namespace App\Tests\Domain\Sequence;

use App\Domain\Sequence\SequenceNumber;
use App\Domain\Sequence\SequenceType;
use PHPUnit\Framework\TestCase;

class SequenceNumberTest extends TestCase
{
    public function testShouldRunWhenMembership(): void
    {
        $sequenceNumber = new SequenceNumber(SequenceType::MEMBERSHIP);

        self::assertSame(SequenceType::MEMBERSHIP, $sequenceNumber->getType());
        self::assertSame('SN', $sequenceNumber->getPrefix());
        self::assertSame(1, $sequenceNumber->getNumber());
        self::assertSame('SN00001', $sequenceNumber->format());
        self::assertSame('SN00001', (string) $sequenceNumber);
    }

    public function testShouldRunWhenNext(): void
    {
        $sequenceNumber = new SequenceNumber(SequenceType::MEMBERSHIP);
        $sequenceNumber->next();

        self::assertSame(SequenceType::MEMBERSHIP, $sequenceNumber->getType());
        self::assertSame('SN', $sequenceNumber->getPrefix());
        self::assertSame(2, $sequenceNumber->getNumber());
        self::assertSame('SN00002', $sequenceNumber->format());
        self::assertSame('SN00002', (string) $sequenceNumber);;
    }
}
