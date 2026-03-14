<?php

namespace App\Tests\Infrastructure\Controller\V1\Customer;

use App\Application\Customer\Creator\CustomerCreate;
use App\Infrastructure\Controller\V1\Customer\CustomerCreateController;
use App\Tests\Doubles\Infrastructure\Persistence\CustomerRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\SequenceNumberRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CustomerCreateControllerTest extends TestCase
{
    use ControllerTestable;
    use FixturePayload;

    private CustomerRepositoryStub $repoCustomer;
    private CustomerCreate $creator;
    private array $payload;
    private CustomerCreateController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoCustomer = new CustomerRepositoryStub();
        $repoSequence = new SequenceNumberRepositoryStub();
        $repoUser = new UserRepositoryStub();

        $this->creator = new CustomerCreate(
            $this->repoCustomer,
            $repoSequence,
            $repoUser,
        );
        $this->controller = new CustomerCreateController();

        $this->payload = $this->getPayload('customer-create');
    }

    public function testShouldFailWhenAlreadyExists(): void
    {
        $this->repoCustomer->put(Ref::CustomerJohnDoe);

        $request = $this->createRequest(request: $this->payload);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke($request, $this->creator);
    }

    public function testShouldRunWhenCreate(): void
    {
        $request = $this->createRequest(request: $this->payload);

        $response = $this->controller->__invoke($request, $this->creator);

        self::assertResponse($response, 201);
    }
}
