<?php

namespace App\Tests\Application\Book\Inventory;

use App\Application\Book\Inventory\BookInventory;
use App\Application\Book\Inventory\BookInventoryDomainHandler;
use App\Application\Book\Inventory\BookInventoryEvent;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\SaleLineRepositoryStub;
use App\Tests\Fixtures\Mothers\BookMother;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;
use function App\Tests\Doubles\makeNullLogger;

class BookInventoryDomainHandlerTest extends TestCase
{
    private BookRepositoryStub $repoBook;
    private PurchaseLineRepositoryStub $repoPurchaseLine;
    private SaleLineRepositoryStub $repoSaleLine;
    private BookInventoryDomainHandler $handler;
    private MockObject $logger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoBook = new BookRepositoryStub();
        $this->repoPurchaseLine = new PurchaseLineRepositoryStub();
        $this->repoSaleLine = new SaleLineRepositoryStub();

        $inventory = new BookInventory(
            $this->repoBook,
            $this->repoPurchaseLine,
            $this->repoSaleLine,
            makeNullLogger(),
        );

        $this->logger = $this->createMock(LoggerInterface::class);
        $this->handler = new BookInventoryDomainHandler(
            $inventory,
            $this->logger,
        );
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->logger->expects($this->once())
            ->method('error');

        $book = BookMother::romeoAndJuliet();
        $event = new BookInventoryEvent($book->getDescriptor());

        $this->handler->__invoke($event);
    }

    public function testShouldRunWhenCalculateInventory(): void
    {
        $this->logger->expects($this->never())
            ->method('error');

        $book = $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $this->repoPurchaseLine->attach(Ref::PurchaseLineAmazonLine1);
        $this->repoSaleLine->attach(Ref::SaleLineJohnDoe1Line2);

        $event = new BookInventoryEvent($book->getDescriptor());

        $this->handler->__invoke($event);

        assertStored($this->repoBook);
        self::assertEquals(1, $this->repoBook->stored->getStock());
    }
}
