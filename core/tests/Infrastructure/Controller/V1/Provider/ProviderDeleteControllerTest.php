<?php

namespace App\Tests\Infrastructure\Controller\V1\Provider;

use App\Application\Provider\Eraser\ProviderDelete;
use App\Infrastructure\Controller\V1\Provider\ProviderDeleteController;
use App\Tests\Doubles\Infrastructure\Persistence\ProviderRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseRepositoryStub;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProviderDeleteControllerTest extends TestCase
{
    use ControllerTestable;

    private ProviderRepositoryStub $repoProvider;
    private PurchaseRepositoryStub $repoPurchase;
    private ProviderDelete $eraser;
    private ProviderDeleteController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoProvider = new ProviderRepositoryStub();
        $this->repoPurchase = new PurchaseRepositoryStub();
        $this->eraser = new ProviderDelete(
            $this->repoProvider,
            $this->repoPurchase,
        );
        $this->controller = new ProviderDeleteController();
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->controller->__invoke('not-found-id', $this->eraser);
    }

    public function testShouldFailWhenBadRequest(): void
    {
        $this->repoProvider->put(Ref::ProviderAmazon);
        $this->repoPurchase->attach(Ref::PurchaseAmazonInv1);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke('12345678', $this->eraser);
    }

    public function testShouldRunWhenRemoved(): void
    {
        $this->repoProvider->put(Ref::ProviderAmazon);

        $response = $this->controller->__invoke('12345678', $this->eraser);

        self::assertResponse($response, 204);
        self::assertNotNull($this->repoProvider->removed);
    }
}
