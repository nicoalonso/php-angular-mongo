<?php

namespace App\Tests\Application\Sequence\Simulator;

use App\Application\Sequence\Simulator\SequenceSimulate;
use App\Domain\Sequence\Exception\InvalidSequenceTypeException;
use App\Tests\Doubles\Infrastructure\Persistence\SequenceNumberRepositoryStub;
use PHPUnit\Framework\TestCase;

class SequenceSimulateTest extends TestCase
{
    private SequenceSimulate $simulate;

    protected function setUp(): void
    {
        parent::setUp();

        $repoSequenceNumber = new SequenceNumberRepositoryStub();
        $this->simulate = new SequenceSimulate($repoSequenceNumber);
    }

    public function testShouldFailWhenWrongType(): void
    {
        $this->expectException(InvalidSequenceTypeException::class);

        $this->simulate->dispatch('wrong-type');
    }

    public function testShouldRunWhenSimulate(): void
    {
        $number = $this->simulate->dispatch('sale');

        self::assertSame('F-00001', $number->format());
    }
}
