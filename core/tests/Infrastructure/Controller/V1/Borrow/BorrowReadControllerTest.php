<?php

namespace App\Tests\Infrastructure\Controller\V1\Borrow;

use App\Application\Borrow\Reader\BorrowRead;
use App\Infrastructure\Controller\V1\Borrow\BorrowReadController;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowRepositoryStub;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BorrowReadControllerTest extends TestCase
{
    use ControllerTestable;

    private BorrowRepositoryStub $repoBorrow;
    private BorrowLineRepositoryStub $repoBorrowLine;
    private BorrowRead $reader;
    private BorrowReadController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoBorrow = new BorrowRepositoryStub();
        $this->repoBorrowLine = new BorrowLineRepositoryStub($this->repoBorrow);
        $this->reader = new BorrowRead($this->repoBorrow, $this->repoBorrowLine);
        $this->controller = new BorrowReadController();
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->controller->__invoke('unknown-book-id', $this->reader);
    }

    public function testShouldRunWhenRead(): void
    {
        $this->repoBorrow->put(Ref::BorrowJohnDoe);
        $this->repoBorrowLine->attachAll();

        $response = $this->controller->__invoke('1234567890', $this->reader);

        self::assertResponse($response);
    }
}
