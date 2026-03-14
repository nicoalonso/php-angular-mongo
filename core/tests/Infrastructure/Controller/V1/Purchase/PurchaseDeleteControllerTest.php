<?php

namespace App\Tests\Infrastructure\Controller\V1\Purchase;

use App\Application\Purchase\Eraser\PurchaseDelete;
use App\Application\Purchase\Eraser\PurchaseDeletedEvent;
use App\Infrastructure\Controller\V1\Purchase\PurchaseDeleteController;
use App\Tests\Doubles\Infrastructure\Bus\DomainBusStub;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseRepositoryStub;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function App\Tests\Doubles\Infrastructure\Bus\assertDispatch;
use function App\Tests\Doubles\Infrastructure\Persistence\assertRemoved;

class PurchaseDeleteControllerTest extends TestCase
{
    use ControllerTestable;

    private PurchaseRepositoryStub $repoPurchase;
    private PurchaseLineRepositoryStub $repoPurchaseLine;
    private DomainBusStub $bus;
    private PurchaseDelete $eraser;
    private PurchaseDeleteController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoPurchase = new PurchaseRepositoryStub();
        $this->repoPurchaseLine = new PurchaseLineRepositoryStub($this->repoPurchase);
        $this->bus = new DomainBusStub();

        $this->eraser = new PurchaseDelete(
            $this->repoPurchase,
            $this->repoPurchaseLine,
            $this->bus,
        );
        $this->controller = new PurchaseDeleteController();
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->controller->__invoke('not-found-id', $this->eraser);
    }

    public function testShouldRunWhenRemoved(): void
    {
        $this->repoPurchase->put(Ref::PurchaseAmazonInv1);
        $this->repoPurchaseLine->attach(Ref::PurchaseLineAmazonLine1);

        $response = $this->controller->__invoke('12345646', $this->eraser);

        self::assertResponse($response, 204);
        assertRemoved($this->repoPurchase);
        assertRemoved($this->repoPurchaseLine);
        assertDispatch($this->bus, PurchaseDeletedEvent::class);
    }
}
