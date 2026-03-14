<?php

namespace App\Tests\Infrastructure\Controller\V1\Purchase;

use App\Application\Purchase\Reader\PurchaseRead;
use App\Infrastructure\Controller\V1\Purchase\PurchaseReadController;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseRepositoryStub;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PurchaseReadControllerTest extends TestCase
{
    use ControllerTestable;

    private PurchaseRepositoryStub $repoPurchase;
    private PurchaseLineRepositoryStub $repoPurchaseLine;
    private PurchaseRead $reader;
    private PurchaseReadController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoPurchase = new PurchaseRepositoryStub();
        $this->repoPurchaseLine = new PurchaseLineRepositoryStub();
        $this->reader = new PurchaseRead($this->repoPurchase, $this->repoPurchaseLine);
        $this->controller = new PurchaseReadController();
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->controller->__invoke('unknown-book-id', $this->reader);
    }

    public function testShouldRunWhenRead(): void
    {
        $this->repoPurchase->put(Ref::PurchaseAmazonInv1);
        $this->repoPurchaseLine->attach(Ref::PurchaseLineAmazonLine1);
        $this->repoPurchaseLine->attach(Ref::PurchaseLineAmazonLine2);

        $response = $this->controller->__invoke('1234567890', $this->reader);

        self::assertResponse($response);
    }
}
