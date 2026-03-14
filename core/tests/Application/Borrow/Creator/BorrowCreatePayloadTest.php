<?php

namespace App\Tests\Application\Borrow\Creator;

use App\Application\Borrow\Creator\BorrowCreatePayload;
use App\Application\Borrow\Creator\Payload\BorrowLinePayload;
use App\Tests\Fixtures\FixturePayload;
use PHPUnit\Framework\TestCase;

class BorrowCreatePayloadTest extends TestCase
{
    use FixturePayload;

    public function testShouldRunWhenCreate(): void
    {
        $data = $this->getPayload('borrow-create');
        $payload = new BorrowCreatePayload($data);

        $this->assertEquals('d6e20876-6f65-5181-a656-b34086d68b4f', $payload->getCustomerId());
        $this->assertCount(1, $payload->getLines());

        /** @var BorrowLinePayload $line1 */
        $line1 = $payload->getLines()->first();
        $this->assertEquals('d6e20876-6f65-5181-a656-b34086d68b4f', $line1->getBookId());
    }
}
