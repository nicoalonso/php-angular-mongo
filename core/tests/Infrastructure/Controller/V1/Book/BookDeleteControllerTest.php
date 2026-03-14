<?php

namespace App\Tests\Infrastructure\Controller\V1\Book;

use App\Application\Book\Eraser\BookDelete;
use App\Infrastructure\Controller\V1\Book\BookDeleteController;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\SaleLineRepositoryStub;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookDeleteControllerTest extends TestCase
{
    use ControllerTestable;

    private BookRepositoryStub $repoBook;
    private PurchaseLineRepositoryStub $repoPurchaseLine;
    private SaleLineRepositoryStub $repoSaleLine;
    private BorrowLineRepositoryStub $repoBorrowLine;
    private BookDelete $eraser;
    private BookDeleteController $controller;

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
        $this->controller = new BookDeleteController();
    }


    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->controller->__invoke('invalid-id', $this->eraser);
    }

    public function testShouldFailWhenPurchaseAssociated(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $this->repoPurchaseLine->attach(Ref::PurchaseLineAmazonLine1);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke('12345678', $this->eraser);
    }

    public function testShouldFailWhenSaleAssociated(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $this->repoSaleLine->attach(Ref::SaleLineJohnDoe1Line1);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke('12345678', $this->eraser);
    }

    public function testShouldFailWhenBorrowAssociated(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $this->repoBorrowLine->attach(Ref::BorrowLineJohnRomeoAndJuliet);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke('12345678', $this->eraser);
    }

    public function testShouldRunWhenRemoved(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);

        $response = $this->controller->__invoke('12345678', $this->eraser);

        self::assertResponse($response, 204);
        self::assertNotNull($this->repoBook->removed);
    }
}
