<?php

namespace App\Tests\Application\Borrow\CheckIn;

use App\Application\Borrow\CheckIn\BorrowCheckInPayload;
use App\Application\Borrow\Creator\Payload\BorrowLinePayload;
use App\Tests\Fixtures\FixturePayload;
use PHPUnit\Framework\TestCase;

class BorrowCheckInPayloadTest extends TestCase
{
    use FixturePayload;

    public function testShouldRunWhenCreate(): void
    {
        $data = $this->getPayload('borrow-checkin');
        $payload = new BorrowCheckInPayload($data);

        $this->assertCount(3, $payload->getLines());

        /** @var BorrowLinePayload $line1 */
        $line1 = $payload->getLines()->first();
        $this->assertEquals('d6e20876-6f65-5181-a656-b34086d68b4f', $line1->getLineId());
        $this->assertEquals('d6e20876-6f65-5181-a656-b34086d68b4f', $line1->getBookId());
        $this->assertTrue($line1->isReturned());
    }
}
