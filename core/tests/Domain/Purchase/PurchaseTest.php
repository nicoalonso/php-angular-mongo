<?php

namespace App\Tests\Domain\Purchase;

use App\Domain\Provider\Provider;
use App\Domain\Purchase\Exception\InvalidPurchaseDateException;
use App\Domain\Purchase\Purchase;
use App\Domain\Purchase\PurchaseInvoice;
use App\Tests\Fixtures\Mothers\ProviderMother;
use App\Tests\Fixtures\Mothers\PurchaseInvoiceMother;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class PurchaseTest extends TestCase
{
    private Provider $provider;
    private PurchaseInvoice $invoice;

    protected function setUp(): void
    {
        parent::setUp();

        $this->provider = ProviderMother::amazon();
        $this->invoice = PurchaseInvoiceMother::invoice1();
    }

    public function testShouldFailWhenInvalidPurchaseDate(): void
    {
        $this->expectException(InvalidPurchaseDateException::class);
        new Purchase(
            $this->provider,
            new DateTimeImmutable('+2 days'),
            $this->invoice,
            'test',
        );
    }

    public function testShouldRunWhenCreate(): void
    {
        $purchase = new Purchase(
            $this->provider,
            new DateTimeImmutable('today'),
            $this->invoice,
            'test',
        );

        $this->assertEquals($this->provider->getDescriptor(), $purchase->getProvider());
        $this->assertEquals(new DateTimeImmutable('today'), $purchase->getPurchasedAt());
        $this->assertEquals($this->invoice, $purchase->getInvoice());
    }

    public function testShouldRunWhenModify(): void
    {
        $purchase = new Purchase(
            $this->provider,
            new DateTimeImmutable('today'),
            $this->invoice,
            'test',
        );

        $newProvider = ProviderMother::bestBuy();
        $newInvoice = PurchaseInvoiceMother::invoice2();

        $purchase->modify(
            $newProvider,
            new DateTimeImmutable('yesterday'),
            $newInvoice,
            'test',
        );

        $this->assertEquals($newProvider->getDescriptor(), $purchase->getProvider());
        $this->assertEquals(new DateTimeImmutable('yesterday'), $purchase->getPurchasedAt());
        $this->assertEquals($newInvoice, $purchase->getInvoice());
    }
}
