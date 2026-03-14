<?php

namespace App\Tests\Infrastructure\Controller\V1\Customer;

use App\Application\Customer\List\CustomerList;
use App\Infrastructure\Controller\V1\Customer\CustomerListController;
use App\Tests\Doubles\Infrastructure\Persistence\CustomerRepositoryStub;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CustomerListControllerTest extends TestCase
{
    use ControllerTestable;

    private CustomerRepositoryStub $repository;
    private CustomerList $lister;
    private CustomerListController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new CustomerRepositoryStub();
        $this->lister = new CustomerList($this->repository);
        $this->controller = new CustomerListController();
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
