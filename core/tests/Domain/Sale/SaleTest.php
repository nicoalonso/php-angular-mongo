<?php

namespace App\Tests\Domain\Sale;

use App\Domain\Customer\Customer;
use App\Domain\Sale\Exception\InvalidSaleInvoiceNumberException;
use App\Domain\Sale\Sale;
use App\Domain\Sale\SaleInvoice;
use App\Tests\Fixtures\Mothers\CustomerMother;
use App\Tests\Fixtures\Mothers\SaleInvoiceMother;
use PHPUnit\Framework\TestCase;

class SaleTest extends TestCase
{
    private Customer $customer;
    private SaleInvoice $invoice;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = CustomerMother::johnDoe();
        $this->invoice = SaleInvoiceMother::johnDoeSale1();
    }

    public function testShouldFailWhenNumberEmpty(): void
    {
        $this->expectException(InvalidSaleInvoiceNumberException::class);

        new Sale(
            $this->customer,
            '',
            $this->invoice,
            'test',
        );
    }

    public function testShouldRunWhenCreate(): void
    {
        $sale = new Sale(
            $this->customer,
            'F-00001',
            $this->invoice,
            'test',
        );

        $this->assertEquals($this->customer->getDescriptor(), $sale->getCustomer());
        $this->assertEquals('F-00001', $sale->getNumber());
        $this->assertEquals($this->invoice, $sale->getInvoice());
    }
}
