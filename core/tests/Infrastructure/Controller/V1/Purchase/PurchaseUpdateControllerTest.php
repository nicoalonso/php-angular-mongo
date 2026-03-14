<?php

namespace App\Tests\Infrastructure\Controller\V1\Purchase;

use App\Application\Purchase\Updater\PurchaseUpdate;
use App\Application\Purchase\Updater\PurchaseUpdatedEvent;
use App\Infrastructure\Controller\V1\Purchase\PurchaseUpdateController;
use App\Tests\Doubles\Infrastructure\Bus\DomainBusStub;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\ProviderRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function App\Tests\Doubles\Infrastructure\Bus\assertDispatch;
use function App\Tests\Doubles\Infrastructure\Persistence\assertRemoved;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class PurchaseUpdateControllerTest extends TestCase
{
    use ControllerTestable;
    use FixturePayload;

    private PurchaseRepositoryStub $repoPurchase;
    private PurchaseLineRepositoryStub $repoPurchaseLine;
    private ProviderRepositoryStub $repoProvider;
    private BookRepositoryStub $repoBook;
    private DomainBusStub $bus;
    private PurchaseUpdate $updater;
    private PurchaseUpdateController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoProvider = new ProviderRepositoryStub();
        $this->repoPurchase = new PurchaseRepositoryStub($this->repoProvider);
        $this->repoBook = new BookRepositoryStub();
        $this->repoPurchaseLine = new PurchaseLineRepositoryStub(
            $this->repoPurchase,
            $this->repoBook,
        );
        $this->bus = new DomainBusStub();
        $repoUser = new UserRepositoryStub();

        $this->updater = new PurchaseUpdate(
            $this->repoPurchase,
            $this->repoPurchaseLine,
            $this->repoProvider,
            $this->repoBook,
            $repoUser,
            $this->bus,
        );
        $this->controller = new PurchaseUpdateController();
    }

    public function testShouldFailWhenNotFound(): void
    {
        $data = $this->getPayload('purchase');
        $request = $this->createRequest(request: $data);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->__invoke('invalid-id', $request, $this->updater);
    }

    public function testShouldFailWhenProviderNotFound(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);

        $this->repoPurchase->put(Ref::PurchaseAmazonInv1);
        $this->repoPurchaseLine->attach(Ref::PurchaseLineAmazonLine1);

        $data = $this->getPayload('purchase');
        $request = $this->createRequest(request: $data);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke('invalid-id', $request, $this->updater);
    }

    public function testShouldRunWhenModify(): void
    {
        $this->repoProvider->put(Ref::ProviderAmazon);
        $this->repoBook->put(Ref::BookRomeoAndJuliet);

        $this->repoPurchase->put(Ref::PurchaseAmazonInv1);
        $this->repoPurchaseLine->attach(Ref::PurchaseLineAmazonLine1);
        $this->repoPurchaseLine->attach(Ref::PurchaseLineAmazonLine2);

        $data = $this->getPayload('purchase');
        $request = $this->createRequest(request: $data);

        $response = $this->controller->__invoke('invalid-id', $request, $this->updater);

        self::assertResponse($response, 204);
        assertStored($this->repoPurchase);
        assertStored($this->repoPurchaseLine);
        assertRemoved($this->repoPurchaseLine);
        assertDispatch($this->bus, PurchaseUpdatedEvent::class);
    }
}
