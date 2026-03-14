<?php

namespace App\Tests\Application\Book\Inventory;

use App\Application\Book\Inventory\BookInventory;
use App\Domain\Book\Exception\BookNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\SaleLineRepositoryStub;
use App\Tests\Fixtures\Mothers\BookMother;
use App\Tests\Fixtures\Mothers\PurchaseLineMother;
use App\Tests\Fixtures\Mothers\SaleLineMother;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;
use function App\Tests\Doubles\makeNullLogger;

class BookInventoryTest extends TestCase
{
    private BookRepositoryStub $repoBook;
    private PurchaseLineRepositoryStub $repoPurchaseLine;
    private SaleLineRepositoryStub $repoSaleLine;
    private BookInventory $inventory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoBook = new BookRepositoryStub();
        $this->repoPurchaseLine = new PurchaseLineRepositoryStub();
        $this->repoSaleLine = new SaleLineRepositoryStub();
        $logger = makeNullLogger();

        $this->inventory = new BookInventory(
            $this->repoBook,
            $this->repoPurchaseLine,
            $this->repoSaleLine,
            $logger,
        );
    }

    public function testShouldFailWhenBookNotFound(): void
    {
        $book = BookMother::romeoAndJuliet();

        $this->expectException(BookNotFoundException::class);
        $this->inventory->dispatch($book->getDescriptor());
    }

    public function testShouldZeroStockWhenLinesNotFound(): void
    {
        $book = $this->repoBook->put(Ref::BookRomeoAndJuliet);

        $this->inventory->dispatch($book->getDescriptor());

        assertStored($this->repoBook);
        self::assertEquals(0, $this->repoBook->stored->getStock());
    }

    public function testShouldStockPositiveWhenHasPurchaseLines(): void
    {
        $book = $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $this->repoPurchaseLine->attach(Ref::PurchaseLineAmazonLine1);

        $this->inventory->dispatch($book->getDescriptor());

        assertStored($this->repoBook);
        self::assertEquals(2, $this->repoBook->stored->getStock());
    }

    public function testShouldStockPositiveWhenHasPurchaseAndSaleLines(): void
    {
        $book = $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $this->repoPurchaseLine->attach(Ref::PurchaseLineAmazonLine1);
        $this->repoSaleLine->attach(Ref::SaleLineJohnDoe1Line2);

        $this->inventory->dispatch($book->getDescriptor());

        assertStored($this->repoBook);
        self::assertEquals(1, $this->repoBook->stored->getStock());
    }

    public function testShouldStockZeroWhenEqualPurchasesAndSales(): void
    {
        $book = $this->repoBook->put(Ref::BookRomeoAndJuliet);

        $purchaseLine = PurchaseLineMother::amazonLine1(
            book: $book,
            quantity: 2,
        );
        $this->repoPurchaseLine->manualAttach($purchaseLine);

        $saleLine = SaleLineMother::johnSale1Line2(
            book: $book,
            quantity: 2,
        );
        $this->repoSaleLine->manualAttach($saleLine);

        $this->inventory->dispatch($book->getDescriptor());

        assertStored($this->repoBook);
        self::assertEquals(0, $this->repoBook->stored->getStock());
    }

    public function testShouldStockZeroWhenOnlyHasSales(): void
    {
        $book = $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $this->repoSaleLine->attach(Ref::SaleLineJohnDoe1Line2);

        $this->inventory->dispatch($book->getDescriptor());

        assertStored($this->repoBook);
        self::assertEquals(0, $this->repoBook->stored->getStock());
    }
}
