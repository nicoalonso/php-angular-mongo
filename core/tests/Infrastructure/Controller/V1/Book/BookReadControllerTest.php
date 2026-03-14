<?php

namespace App\Tests\Infrastructure\Controller\V1\Book;

use App\Application\Book\Reader\BookRead;
use App\Infrastructure\Controller\V1\Book\BookReadController;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookReadControllerTest extends TestCase
{
    use ControllerTestable;

    private BookRepositoryStub $repoBook;
    private BookRead $reader;
    private BookReadController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoBook = new BookRepositoryStub();
        $this->reader = new BookRead($this->repoBook);
        $this->controller = new BookReadController();
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->controller->__invoke('unknown-book-id', $this->reader);
    }

    public function testShouldRunWhenRead(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);

        $response = $this->controller->__invoke('1234567890', $this->reader);

        $data = self::assertResponse($response);
        self::assertEquals('Romeo and Juliet', $data['title']);
    }
}
