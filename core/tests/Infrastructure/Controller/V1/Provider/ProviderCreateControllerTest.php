<?php

namespace App\Tests\Infrastructure\Controller\V1\Provider;

use App\Application\Provider\Creator\ProviderCreate;
use App\Infrastructure\Controller\V1\Provider\ProviderCreateController;
use App\Tests\Doubles\Infrastructure\Persistence\ProviderRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class ProviderCreateControllerTest extends TestCase
{
    use ControllerTestable;
    use FixturePayload;

    private ProviderRepositoryStub $repoProvider;
    private ProviderCreate $creator;
    private array $payload;
    private ProviderCreateController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoProvider = new ProviderRepositoryStub();
        $repoUser = new UserRepositoryStub();

        $this->creator = new ProviderCreate($this->repoProvider, $repoUser);
        $this->controller = new ProviderCreateController();

        $this->payload = $this->getPayload('provider');
    }

    public function testShouldFailWhenBadRequest(): void
    {
        $this->repoProvider->put(Ref::ProviderAmazon);
        $request = $this->createRequest(request: $this->payload);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke($request, $this->creator);
    }

    public function testShouldRunWhenCreate(): void
    {
        $request = $this->createRequest(request: $this->payload);

        $response = $this->controller->__invoke($request, $this->creator);

        self::assertResponse($response, 201);
        assertStored($this->repoProvider);
    }
}
