<?php

namespace App\Tests\Domain\Purchase;

use App\Domain\Purchase\Exception\InvalidPurchaseInvoiceNumberException;
use App\Domain\Purchase\PurchaseInvoice;
use PHPUnit\Framework\TestCase;

class PurchaseInvoiceTest extends TestCase
{
    public function testShouldFailWhenNumberEmpty(): void
    {
        $this->expectException(InvalidPurchaseInvoiceNumberException::class);
        new PurchaseInvoice(
            '',
            100,
            20,
            120,
        );
    }

    public function testShouldRunWhenCreate(): void
    {
        $purchase = new PurchaseInvoice(
            'INV-001',
            100,
            20,
            120,
        );

        $this->assertEquals('INV-001', $purchase->getNumber());
        $this->assertEquals(100, $purchase->getAmount());
        $this->assertEquals(20, $purchase->getTaxes());
        $this->assertEquals(120, $purchase->getTotal());
    }
}
