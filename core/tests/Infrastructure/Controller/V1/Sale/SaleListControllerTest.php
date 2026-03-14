<?php

namespace App\Tests\Infrastructure\Controller\V1\Sale;

use App\Application\Sale\List\SaleList;
use App\Infrastructure\Controller\V1\Sale\SaleListController;
use App\Tests\Doubles\Infrastructure\Persistence\SaleRepositoryStub;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SaleListControllerTest extends TestCase
{
    use ControllerTestable;

    private SaleRepositoryStub $repository;
    private SaleList $lister;
    private SaleListController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new SaleRepositoryStub();
        $this->lister = new SaleList($this->repository);
        $this->controller = new SaleListController();
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
