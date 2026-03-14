<?php

namespace App\Tests\Application\Purchase\Supplier;

use App\Application\Book\Inventory\BookInventoryEvent;
use App\Application\Purchase\Supplier\PurchaseSupply;
use App\Tests\Doubles\Infrastructure\Bus\DomainBusStub;
use App\Tests\Fixtures\Mothers\BookMother;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Bus\assertDispatch;

class PurchaseSupplyTest extends TestCase
{
    private DomainBusStub $bus;
    private PurchaseSupply $supplier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bus = new DomainBusStub();
        $this->supplier = new PurchaseSupply($this->bus);
    }

    public function testShouldRunWhenSupply(): void
    {
        $books = [
            BookMother::donQuijote()->getDescriptor(),
        ];

        $this->supplier->dispatch($books);

        assertDispatch($this->bus, BookInventoryEvent::class);
    }
}
