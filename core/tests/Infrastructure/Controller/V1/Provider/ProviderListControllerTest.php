<?php

namespace App\Tests\Infrastructure\Controller\V1\Provider;

use App\Application\Provider\List\ProviderList;
use App\Infrastructure\Controller\V1\Provider\ProviderListController;
use App\Tests\Doubles\Infrastructure\Persistence\ProviderRepositoryStub;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProviderListControllerTest extends TestCase
{
    use ControllerTestable;

    private ProviderRepositoryStub $repository;
    private ProviderList $lister;
    private ProviderListController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new ProviderRepositoryStub();
        $this->lister = new ProviderList($this->repository);
        $this->controller = new ProviderListController();
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
