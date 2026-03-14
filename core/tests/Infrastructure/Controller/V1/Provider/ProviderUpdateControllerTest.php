<?php

namespace App\Tests\Infrastructure\Controller\V1\Provider;

use App\Application\Provider\Updater\ProviderUpdate;
use App\Infrastructure\Controller\V1\Provider\ProviderUpdateController;
use App\Tests\Doubles\Infrastructure\Persistence\ProviderRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class ProviderUpdateControllerTest extends TestCase
{
    use ControllerTestable;
    use FixturePayload;

    private ProviderRepositoryStub $repoProvider;
    private ProviderUpdate $updater;
    private array $payload;
    private ProviderUpdateController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoProvider = new ProviderRepositoryStub();
        $repoUser = new UserRepositoryStub();
        $this->updater = new ProviderUpdate($this->repoProvider, $repoUser);
        $this->controller = new ProviderUpdateController();

        $this->payload = $this->getPayload('provider');
    }

    public function testShouldFailWhenNotFound(): void
    {
        $request = $this->createRequest(request: $this->payload);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->__invoke('invalid-id', $request, $this->updater);
    }

    public function testShouldRunWhenUpdated(): void
    {
        $this->repoProvider->put(Ref::ProviderAmazon);
        $request = $this->createRequest(request: $this->payload);

        $response = $this->controller->__invoke('invalid-id', $request, $this->updater);

        self::assertResponse($response, 204);
        assertStored($this->repoProvider);
    }
}
