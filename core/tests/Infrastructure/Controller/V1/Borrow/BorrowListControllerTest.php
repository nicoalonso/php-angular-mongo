<?php

namespace App\Tests\Infrastructure\Controller\V1\Borrow;

use App\Application\Borrow\List\BorrowList;
use App\Infrastructure\Controller\V1\Borrow\BorrowListController;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowRepositoryStub;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BorrowListControllerTest extends TestCase
{
    use ControllerTestable;

    private BorrowRepositoryStub $repository;
    private BorrowList $lister;
    private BorrowListController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new BorrowRepositoryStub();
        $this->lister = new BorrowList($this->repository);
        $this->controller = new BorrowListController();
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
