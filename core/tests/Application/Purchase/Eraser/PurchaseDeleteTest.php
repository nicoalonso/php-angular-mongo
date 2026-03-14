<?php

namespace App\Tests\Application\Purchase\Eraser;

use App\Application\Purchase\Eraser\PurchaseDelete;
use App\Application\Purchase\Eraser\PurchaseDeletedEvent;
use App\Domain\Purchase\Exception\PurchaseNotFoundException;
use App\Tests\Doubles\Infrastructure\Bus\DomainBusStub;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseRepositoryStub;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Bus\assertDispatch;
use function App\Tests\Doubles\Infrastructure\Persistence\assertRemoved;

class PurchaseDeleteTest extends TestCase
{
    private PurchaseRepositoryStub $repoPurchase;
    private PurchaseLineRepositoryStub $repoPurchaseLine;
    private DomainBusStub $bus;
    private PurchaseDelete $eraser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoPurchase = new PurchaseRepositoryStub();
        $this->repoPurchaseLine = new PurchaseLineRepositoryStub($this->repoPurchase);
        $this->bus = new DomainBusStub();

        $this->eraser = new PurchaseDelete(
            $this->repoPurchase,
            $this->repoPurchaseLine,
            $this->bus,
        );
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(PurchaseNotFoundException::class);
        $this->eraser->dispatch('not-found-id');
    }

    public function testShouldRunWhenRemoved(): void
    {
        $this->repoPurchase->put(Ref::PurchaseAmazonInv1);
        $this->repoPurchaseLine->attach(Ref::PurchaseLineAmazonLine1);

        $this->eraser->dispatch('12345646');

        assertRemoved($this->repoPurchase);
        assertRemoved($this->repoPurchaseLine);
        assertDispatch($this->bus, PurchaseDeletedEvent::class);
    }
}
