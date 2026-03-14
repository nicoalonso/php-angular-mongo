<?php

namespace App\Tests\Application\Purchase\Reader;

use App\Application\Purchase\Reader\PurchaseRead;
use App\Domain\Purchase\Exception\PurchaseNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseRepositoryStub;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;

class PurchaseReadTest extends TestCase
{
    private PurchaseRepositoryStub $repoPurchase;
    private PurchaseLineRepositoryStub $repoPurchaseLine;
    private PurchaseRead $reader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoPurchase = new PurchaseRepositoryStub();
        $this->repoPurchaseLine = new PurchaseLineRepositoryStub();
        $this->reader = new PurchaseRead($this->repoPurchase, $this->repoPurchaseLine);
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(PurchaseNotFoundException::class);

        $this->reader->dispatch('unknown-purchase-id');
    }

    public function testShouldRunWhenRead(): void
    {
        $this->repoPurchase->put(Ref::PurchaseAmazonInv1);
        $this->repoPurchaseLine->attach(Ref::PurchaseLineAmazonLine1);
        $this->repoPurchaseLine->attach(Ref::PurchaseLineAmazonLine2);

        $purchase = $this->reader->dispatch('1234567890');

        self::assertEquals('Amazon', $purchase->getProvider()->getName());
        self::assertCount(2, $purchase->getLines());
    }
}
