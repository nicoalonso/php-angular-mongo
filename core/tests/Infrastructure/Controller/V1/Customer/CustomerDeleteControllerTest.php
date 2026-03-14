<?php

namespace App\Tests\Infrastructure\Controller\V1\Customer;

use App\Application\Customer\Eraser\CustomerDelete;
use App\Infrastructure\Controller\V1\Customer\CustomerDeleteController;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\CustomerRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\SaleRepositoryStub;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomerDeleteControllerTest extends TestCase
{
    use ControllerTestable;

    private CustomerRepositoryStub $repoCustomer;
    private SaleRepositoryStub $repoSale;
    private BorrowRepositoryStub $repoBorrow;
    private CustomerDelete $eraser;
    private CustomerDeleteController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoCustomer = new CustomerRepositoryStub();
        $this->repoSale = new SaleRepositoryStub();
        $this->repoBorrow = new BorrowRepositoryStub();
        $this->eraser = new CustomerDelete(
            $this->repoCustomer,
            $this->repoSale,
            $this->repoBorrow,
        );
        $this->controller = new CustomerDeleteController();
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->controller->__invoke('non-existing-id', $this->eraser);
    }

    public function testShouldFailWhenBadRequest(): void
    {
        $this->repoCustomer->put(Ref::CustomerJohnDoe);
        $this->repoBorrow->attach(Ref::BorrowJohnDoe);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke('12345678', $this->eraser);
    }

    public function testShouldRunWhenRemove(): void
    {
        $this->repoCustomer->put(Ref::CustomerJohnDoe);

        $response = $this->controller->__invoke('12345678', $this->eraser);

        self::assertResponse($response, 204);
        self::assertNotNull($this->repoCustomer->removed);
    }
}
