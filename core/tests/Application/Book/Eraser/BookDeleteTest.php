<?php

namespace App\Tests\Application\Book\Eraser;

use App\Application\Book\Eraser\BookAssociatedException;
use App\Application\Book\Eraser\BookDelete;
use App\Domain\Book\Exception\BookNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\SaleLineRepositoryStub;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;

class BookDeleteTest extends TestCase
{
    private BookRepositoryStub $repoBook;
    private PurchaseLineRepositoryStub $repoPurchaseLine;
    private SaleLineRepositoryStub $repoSaleLine;
    private BorrowLineRepositoryStub $repoBorrowLine;
    private BookDelete $eraser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoBook = new BookRepositoryStub();
        $this->repoPurchaseLine = new PurchaseLineRepositoryStub(repoBook: $this->repoBook);
        $this->repoSaleLine = new SaleLineRepositoryStub(repoBook: $this->repoBook);
        $this->repoBorrowLine = new BorrowLineRepositoryStub(repoBook: $this->repoBook);

        $this->eraser = new BookDelete(
            $this->repoBook,
            $this->repoPurchaseLine,
            $this->repoSaleLine,
            $this->repoBorrowLine,
        );
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(BookNotFoundException::class);
        $this->eraser->dispatch('invalid-id');
    }

    public function testShouldFailWhenPurchaseAssociated(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $this->repoPurchaseLine->attach(Ref::PurchaseLineAmazonLine1);

        $this->expectException(BookAssociatedException::class);
        $this->eraser->dispatch('12345678');
    }

    public function testShouldFailWhenSaleAssociated(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $this->repoSaleLine->attach(Ref::SaleLineJohnDoe1Line1);

        $this->expectException(BookAssociatedException::class);
        $this->eraser->dispatch('12345678');
    }

    public function testShouldFailWhenBorrowAssociated(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $this->repoBorrowLine->attach(Ref::BorrowLineJohnRomeoAndJuliet);

        $this->expectException(BookAssociatedException::class);
        $this->eraser->dispatch('12345678');
    }

    public function testShouldRunWhenRemove(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);

        $this->eraser->dispatch('12345678');

        self::assertNotNull($this->repoBook->removed);
    }
}
