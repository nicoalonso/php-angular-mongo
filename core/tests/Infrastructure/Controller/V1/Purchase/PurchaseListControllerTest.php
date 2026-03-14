<?php

namespace App\Tests\Infrastructure\Controller\V1\Purchase;

use App\Application\Purchase\List\PurchaseList;
use App\Infrastructure\Controller\V1\Purchase\PurchaseListController;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseRepositoryStub;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PurchaseListControllerTest extends TestCase
{
    use ControllerTestable;

    private PurchaseRepositoryStub $repository;
    private PurchaseList $lister;
    private PurchaseListController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new PurchaseRepositoryStub();
        $this->lister = new PurchaseList($this->repository);
        $this->controller = new PurchaseListController();
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
