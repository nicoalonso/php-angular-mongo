<?php

namespace App\Tests\Application\Purchase\Supplier;

use App\Application\Book\Inventory\BookInventoryEvent;
use App\Application\Purchase\Creator\PurchaseCreatedEvent;
use App\Application\Purchase\Eraser\PurchaseDeletedEvent;
use App\Application\Purchase\Supplier\PurchaseSupply;
use App\Application\Purchase\Supplier\PurchaseSupplyHandler;
use App\Application\Purchase\Updater\PurchaseUpdatedEvent;
use App\Domain\Book\BookDescriptor;
use App\Domain\Purchase\Purchase;
use App\Tests\Doubles\Infrastructure\Bus\DomainBusStub;
use App\Tests\Fixtures\Mothers\BookMother;
use App\Tests\Fixtures\Mothers\PurchaseMother;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Bus\assertDispatch;

class PurchaseSupplyHandlerTest extends TestCase
{
    private DomainBusStub $bus;
    private PurchaseSupplyHandler $handler;

    private Purchase $purchase;
    /** @var BookDescriptor[] */
    private array $books;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bus = new DomainBusStub();
        $supplier = new PurchaseSupply($this->bus);
        $this->handler = new PurchaseSupplyHandler($supplier);

        $this->purchase = PurchaseMother::amazonInv1();
        $this->books = [
            BookMother::romeoAndJuliet()->getDescriptor(),
        ];
    }

    public function testShouldRunWhenHandleCreated(): void
    {
        $event = new PurchaseCreatedEvent($this->purchase, $this->books);

        $this->handler->handleCreated($event);

        assertDispatch($this->bus, BookInventoryEvent::class);
    }

    public function testShouldRunWhenHandleUpdated(): void
    {
        $event = new PurchaseUpdatedEvent($this->purchase, $this->books);

        $this->handler->handleUpdated($event);

        assertDispatch($this->bus, BookInventoryEvent::class);
    }

    public function testShouldRunWhenHandleDeleted(): void
    {
        $event = new PurchaseDeletedEvent($this->purchase, $this->books);

        $this->handler->handleDeleted($event);

        assertDispatch($this->bus, BookInventoryEvent::class);
    }
}
