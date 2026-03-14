<?php

namespace App\Tests\Infrastructure\Controller\V1\Book;

use App\Application\Book\List\BookList;
use App\Infrastructure\Controller\V1\Book\BookListController;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BookListControllerTest extends TestCase
{
    use ControllerTestable;

    private BookRepositoryStub $repository;
    private BookList $lister;
    private BookListController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new BookRepositoryStub();
        $this->lister = new BookList($this->repository);
        $this->controller = new BookListController();
    }

    public function testShouldFailWhenBadRequest(): void
    {
        $request = $this->createRequest([
            'test' => 'value',
        ]);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke($request, $this->lister);
    }

    public function testShouldRunWhenDispatch(): void
    {
        $this->repository->attachAll();

        $request = $this->createRequest();
        $response = $this->controller->__invoke($request, $this->lister);

        $data = self::assertResponse($response);
        self::assertGreaterThanOrEqual(1, $data);
    }
}
