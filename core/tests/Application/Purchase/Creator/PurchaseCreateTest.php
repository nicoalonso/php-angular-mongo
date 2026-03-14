<?php

namespace App\Tests\Application\Purchase\Creator;

use App\Application\Purchase\Creator\PurchaseCreate;
use App\Application\Purchase\Creator\PurchaseCreatedEvent;
use App\Application\Purchase\Creator\PurchaseCreatePayload;
use App\Domain\Book\Exception\BookNotFoundException;
use App\Domain\Provider\Exception\ProviderNotFoundException;
use App\Domain\Purchase\Exception\InvalidPurchaseDateException;
use App\Domain\Purchase\Exception\PurchaseAlreadyExistsException;
use App\Tests\Doubles\Infrastructure\Bus\DomainBusStub;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\ProviderRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Bus\assertDispatch;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class PurchaseCreateTest extends TestCase
{
    use FixturePayload;

    private PurchaseRepositoryStub $repoPurchase;
    private PurchaseLineRepositoryStub $repoPurchaseLine;
    private ProviderRepositoryStub $repoProvider;
    private BookRepositoryStub $repoBook;
    private DomainBusStub $bus;
    private PurchaseCreate $creator;

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
    }

    public function testShouldFailWhenInvalidPurchaseDate(): void
    {
        $data = $this->override(purchasedAt: '')
            ->getPayload('purchase');
        $payload = new PurchaseCreatePayload($data);

        $this->expectException(InvalidPurchaseDateException::class);
        $this->creator->dispatch($payload);
    }

    public function testShouldFailWhenBookNotFound(): void
    {
        $data = $this->getPayload('purchase');
        $payload = new PurchaseCreatePayload($data);

        $this->expectException(BookNotFoundException::class);
        $this->creator->dispatch($payload);
    }

    public function testShouldFailWhenAlreadyExists(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $this->repoPurchase->put(Ref::PurchaseAmazonInv1);

        $data = $this->getPayload('purchase');
        $payload = new PurchaseCreatePayload($data);

        $this->expectException(PurchaseAlreadyExistsException::class);
        $this->creator->dispatch($payload);
    }

    public function testShouldFailWhenProviderNotFound(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);

        $data = $this->getPayload('purchase');
        $payload = new PurchaseCreatePayload($data);

        $this->expectException(ProviderNotFoundException::class);
        $this->creator->dispatch($payload);
    }

    public function testShouldRunWhenCreate(): void
    {
        $this->repoProvider->put(Ref::ProviderAmazon);
        $this->repoBook->put(Ref::BookRomeoAndJuliet);

        $data = $this->getPayload('purchase');
        $payload = new PurchaseCreatePayload($data);

        $purchase = $this->creator->dispatch($payload);

        self::assertEquals('Amazon', $purchase->getProvider()->getName());
        assertStored($this->repoPurchase);
        assertStored($this->repoPurchaseLine);
        assertDispatch($this->bus, PurchaseCreatedEvent::class);
    }
}
