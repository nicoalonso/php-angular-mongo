<?php

namespace App\Tests\Domain\Sale;

use App\Domain\Sale\Exception\InvalidSaleDateException;
use App\Domain\Sale\SaleInvoice;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class SaleInvoiceTest extends TestCase
{
    public function testShouldFailWhenInvalidDate(): void
    {
        $this->expectException(InvalidSaleDateException::class);

        new SaleInvoice(
            new DateTimeImmutable('+2 days'),
            100,
            21,
            21,
            121,
        );
    }

    public function testShouldRunWhenCreate(): void
    {
        $invoice = new SaleInvoice(
            new DateTimeImmutable('today'),
            100,
            21,
            21,
            121,
        );

        $this->assertEquals(new DateTimeImmutable('today'), $invoice->getDate());
        $this->assertEquals(100, $invoice->getAmount());
        $this->assertEquals(21, $invoice->getTaxPercentage());
        $this->assertEquals(21, $invoice->getTaxes());
        $this->assertEquals(121, $invoice->getTotal());
    }
}
