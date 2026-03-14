<?php

namespace App\Tests\Application\Sale\Consumer;

use App\Application\Book\Inventory\BookInventoryEvent;
use App\Application\Sale\Consumer\SaleConsume;
use App\Application\Sale\Consumer\SaleConsumeHandler;
use App\Application\Sale\Creator\SaleCreatedEvent;
use App\Tests\Doubles\Infrastructure\Bus\DomainBusStub;
use App\Tests\Fixtures\Mothers\BookMother;
use App\Tests\Fixtures\Mothers\SaleMother;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Bus\assertDispatch;

class SaleConsumeHandlerTest extends TestCase
{
    private DomainBusStub $bus;
    private SaleConsumeHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bus = new DomainBusStub();
        $consumer = new SaleConsume($this->bus);
        $this->handler = new SaleConsumeHandler($consumer);
    }

    public function testShouldRunWhenHandle(): void
    {
        $sale = SaleMother::johnDoeSale1();
        $book = BookMother::romeoAndJuliet();
        $books = [
            $book->getDescriptor(),
        ];

        $event = new SaleCreatedEvent($sale, $books);

        $this->handler->handleCreated($event);

        assertDispatch($this->bus, BookInventoryEvent::class);
    }
}
