<?php

namespace App\Tests\Infrastructure\Controller\V1\Sale;

use App\Application\Sale\Reader\SaleRead;
use App\Infrastructure\Controller\V1\Sale\SaleReadController;
use App\Tests\Doubles\Infrastructure\Persistence\SaleLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\SaleRepositoryStub;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SaleReadControllerTest extends TestCase
{
    use ControllerTestable;

    private SaleRepositoryStub $repoSale;
    private SaleLineRepositoryStub $repoSaleLine;
    private SaleRead $reader;
    private SaleReadController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoSale = new SaleRepositoryStub();
        $this->repoSaleLine = new SaleLineRepositoryStub();
        $this->reader = new SaleRead($this->repoSale, $this->repoSaleLine);
        $this->controller = new SaleReadController();
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->controller->__invoke('unknown-book-id', $this->reader);
    }

    public function testShouldRunWhenRead(): void
    {
        $this->repoSale->put(Ref::SaleJohnDoe1);
        $this->repoSaleLine->attach(Ref::SaleLineJohnDoe1Line1);
        $this->repoSaleLine->attach(Ref::SaleLineJohnDoe1Line2);

        $response = $this->controller->__invoke('1234567890', $this->reader);

        $data = self::assertResponse($response);
    }
}
