<?php

namespace App\Tests\Infrastructure\Controller\V1\Purchase;

use App\Application\Purchase\Creator\PurchaseCreate;
use App\Application\Purchase\Creator\PurchaseCreatedEvent;
use App\Infrastructure\Controller\V1\Purchase\PurchaseCreateController;
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
use function App\Tests\Doubles\Infrastructure\Bus\assertDispatch;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class PurchaseCreateControllerTest extends TestCase
{
    use ControllerTestable;
    use FixturePayload;

    private PurchaseRepositoryStub $repoPurchase;
    private PurchaseLineRepositoryStub $repoPurchaseLine;
    private ProviderRepositoryStub $repoProvider;
    private BookRepositoryStub $repoBook;
    private DomainBusStub $bus;
    private PurchaseCreate $creator;
    private PurchaseCreateController $controller;

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

        $this->creator = new PurchaseCreate(
            $this->repoPurchase,
            $this->repoPurchaseLine,
            $this->repoProvider,
            $this->repoBook,
            $repoUser,
            $this->bus,
        );
        $this->controller = new PurchaseCreateController();
    }

    public function testShouldFailWhenBadRequest(): void
    {
        $data = $this->override(purchasedAt: '')
            ->getPayload('purchase');
        $request = $this->createRequest(request: $data);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke($request, $this->creator);
    }

    public function testShouldRunWhenCreate(): void
    {
        $this->repoProvider->put(Ref::ProviderAmazon);
        $this->repoBook->put(Ref::BookRomeoAndJuliet);

        $data = $this->getPayload('purchase');
        $request = $this->createRequest(request: $data);

        $response = $this->controller->__invoke($request, $this->creator);

        self::assertResponse($response, 201);
        assertStored($this->repoPurchase);
        assertStored($this->repoPurchaseLine);
        assertDispatch($this->bus, PurchaseCreatedEvent::class);
    }
}
