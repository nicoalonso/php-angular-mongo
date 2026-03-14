<?php

namespace App\Tests\Infrastructure\Controller\V1\Customer;

use App\Application\Customer\Reader\CustomerRead;
use App\Infrastructure\Controller\V1\Customer\CustomerReadController;
use App\Tests\Doubles\Infrastructure\Persistence\CustomerRepositoryStub;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomerReadControllerTest extends TestCase
{
    use ControllerTestable;

    private CustomerRepositoryStub $repoCustomer;
    private CustomerRead $reader;
    private CustomerReadController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoCustomer = new CustomerRepositoryStub();
        $this->reader = new CustomerRead($this->repoCustomer);
        $this->controller = new CustomerReadController();
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->controller->__invoke('unknown-book-id', $this->reader);
    }

    public function testShouldRunWhenRead(): void
    {
        $this->repoCustomer->put(Ref::CustomerJohnDoe);

        $response = $this->controller->__invoke('1234567890', $this->reader);

        $data = self::assertResponse($response);
        self::assertEquals('John', $data['name']);
    }
}
