<?php

namespace App\Tests\Infrastructure\Controller\V1\Book;

use App\Application\Book\Available\BookAvailable;
use App\Domain\Services\BookInspector\BookInspectFactory;
use App\Infrastructure\Controller\V1\Book\BookAvailableController;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowLineRepositoryStub;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookAvailableControllerTest extends TestCase
{
    use ControllerTestable;

    private BookRepositoryStub $repoBook;
    private BookAvailable $available;
    private BookAvailableController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoBook = new BookRepositoryStub();
        $repoBorrowLine = new BorrowLineRepositoryStub(repoBook: $this->repoBook);
        $factory = new BookInspectFactory($repoBorrowLine);
        $this->available = new BookAvailable($this->repoBook, $factory);
        $this->controller = new BookAvailableController();
    }

    public function testShouldFailWhenNotFound(): void
    {
        $request = $this->createRequest(['sale' => true]);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->__invoke('not-existing-id', $request, $this->available);
    }

    public function testShouldRunWhenAvailable(): void
    {
        $book = $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $book->changeStock(10);

        $request = $this->createRequest(['sale' => true]);
        $response = $this->controller->__invoke('123456', $request, $this->available);

        $data = self::assertResponse($response);
        self::assertTrue($data['available']);
    }
}
