<?php

namespace App\Tests\Infrastructure\Controller\V1\Customer;

use App\Application\Customer\Updater\CustomerUpdate;
use App\Infrastructure\Controller\V1\Customer\CustomerUpdateController;
use App\Tests\Doubles\Infrastructure\Persistence\CustomerRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class CustomerUpdateControllerTest extends TestCase
{
    use ControllerTestable;
    use FixturePayload;

    private CustomerRepositoryStub $repoCustomer;
    private CustomerUpdate $updater;
    private CustomerUpdateController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoCustomer = new CustomerRepositoryStub();
        $repoUser = new UserRepositoryStub();
        $this->updater = new CustomerUpdate(
            $this->repoCustomer,
            $repoUser,
        );
        $this->controller = new CustomerUpdateController();
    }

    public function testShouldFailWhenNotFound(): void
    {
        $payload = $this->getPayload('customer-update');
        $request = $this->createRequest(request: $payload);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->__invoke('non-existing-id', $request, $this->updater);
    }

    public function testShouldFailWhenBadRequest(): void
    {
        $this->repoCustomer->put(Ref::CustomerJohnDoe);

        $payload = $this->override(name: '')
            ->getPayload('customer-update');
        $request = $this->createRequest(request: $payload);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke('12345789', $request, $this->updater);
    }

    public function testShouldRunWhenModify(): void
    {
        $this->repoCustomer->put(Ref::CustomerJohnDoe);

        $payload = $this->getPayload('customer-update');
        $request = $this->createRequest(request: $payload);

        $response = $this->controller->__invoke('12345789', $request, $this->updater);

        self::assertResponse($response, 204);
        assertStored($this->repoCustomer);
    }
}
