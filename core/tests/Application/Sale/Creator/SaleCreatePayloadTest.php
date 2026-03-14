<?php

namespace App\Tests\Application\Sale\Creator;

use App\Application\Sale\Creator\Payload\SaleLinePayload;
use App\Application\Sale\Creator\SaleCreatePayload;
use App\Tests\Fixtures\FixturePayload;
use PHPUnit\Framework\TestCase;

class SaleCreatePayloadTest extends TestCase
{
    use FixturePayload;

    public function testShouldRunWhenCreate(): void
    {
        $data = $this->getPayload('sale');
        $payload = new SaleCreatePayload($data);

        self::assertEquals("d6e20876-6f65-5181-a656-b34086d68b4f", $payload->getCustomerId());
        self::assertCount(1, $payload->getLines());

        $invoice = $payload->getInvoice();
        self::assertEquals('2024-01-01', $invoice->getDate()->format('Y-m-d'));
        self::assertEquals(100, $invoice->getAmount());
        self::assertEquals(21, $invoice->getTaxPercentage());
        self::assertEquals(21, $invoice->getTaxes());
        self::assertEquals(121, $invoice->getTotal());

        /** @var SaleLinePayload $line */
        $line = $payload->getLines()->first();
        self::assertEmpty($line->getLineId());
        self::assertEquals("d6e20876-6f65-5181-a656-b34086d68b4f", $line->getBookId());
        self::assertEquals(2, $line->getQuantity());
        self::assertEquals(10.0, $line->getPrice());
        self::assertEquals(0, $line->getDiscount());
        self::assertEquals(20, $line->getTotal());
    }
}
