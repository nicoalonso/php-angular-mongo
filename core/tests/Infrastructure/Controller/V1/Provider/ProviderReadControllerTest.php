<?php

namespace App\Tests\Infrastructure\Controller\V1\Provider;

use App\Application\Provider\Reader\ProviderRead;
use App\Infrastructure\Controller\V1\Provider\ProviderReadController;
use App\Tests\Doubles\Infrastructure\Persistence\ProviderRepositoryStub;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProviderReadControllerTest extends TestCase
{
    use ControllerTestable;

    private ProviderRepositoryStub $repoProvider;
    private ProviderRead $reader;
    private ProviderReadController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoProvider = new ProviderRepositoryStub();
        $this->reader = new ProviderRead($this->repoProvider);
        $this->controller = new ProviderReadController();
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->controller->__invoke('unknown-book-id', $this->reader);
    }

    public function testShouldRunWhenRead(): void
    {
        $this->repoProvider->put(Ref::ProviderBestBuy);

        $response = $this->controller->__invoke('1234567890', $this->reader);

        $data = self::assertResponse($response);
        self::assertEquals('Best Buy', $data['name']);
    }
}
