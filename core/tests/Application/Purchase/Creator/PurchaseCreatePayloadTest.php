<?php

namespace App\Tests\Application\Purchase\Creator;

use App\Application\Purchase\Creator\Payload\PurchaseLinePayload;
use App\Application\Purchase\Creator\PurchaseCreatePayload;
use App\Tests\Fixtures\FixturePayload;
use PHPUnit\Framework\TestCase;

class PurchaseCreatePayloadTest extends TestCase
{
    use FixturePayload;

    public function testShouldRunWhenCreate(): void
    {
        $data = $this->getPayload('purchase');
        $payload = new PurchaseCreatePayload($data);

        self::assertSame("d6e20876-6f65-5181-a656-b34086d68b4f", $payload->getProviderId());
        self::assertSame('2024-06-01', $payload->getPurchasedAt()->format('Y-m-d'));
        self::assertCount(1, $payload->getLines());

        $invoice = $payload->getInvoice();
        self::assertSame("INV-001", $invoice->getNumber());
        self::assertEquals(100.0, $invoice->getAmount());
        self::assertEquals(20, $invoice->getTaxes());
        self::assertEquals(120, $invoice->getTotal());

        /** @var PurchaseLinePayload $line */
        $line = $payload->getLines()->first();
        self::assertSame("d6e20876-6f65-5181-a656-b34086d68b4f", $line->getLineId());
        self::assertSame("d6e20876-6f65-5181-a656-b34086d68b4f", $line->getBookId());
        self::assertSame(2, $line->getQuantity());
        self::assertEquals(10.0, $line->getUnitPrice());
        self::assertEquals(5.0, $line->getDiscountPercentage());
        self::assertEquals(19.0, $line->getTotal());
    }
}
