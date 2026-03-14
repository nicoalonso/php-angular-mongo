<?php

namespace App\Tests\Application\Purchase\Updater;

use App\Application\Purchase\Updater\PurchaseUpdate;
use App\Application\Purchase\Updater\PurchaseUpdatedEvent;
use App\Application\Purchase\Updater\PurchaseUpdatePayload;
use App\Domain\Purchase\Exception\PurchaseNotFoundException;
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
use function App\Tests\Doubles\Infrastructure\Persistence\assertRemoved;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class PurchaseUpdateTest extends TestCase
{
    use FixturePayload;

    private PurchaseRepositoryStub $repoPurchase;
    private PurchaseLineRepositoryStub $repoPurchaseLine;
    private ProviderRepositoryStub $repoProvider;
    private BookRepositoryStub $repoBook;
    private DomainBusStub $bus;
    private PurchaseUpdate $updater;

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
    }

    public function testShouldFailWhenNotFound(): void
    {
        $data = $this->getPayload('purchase');
        $payload = new PurchaseUpdatePayload($data);

        $this->expectException(PurchaseNotFoundException::class);
        $this->updater->dispatch('invalid-id', $payload);
    }

    public function testShouldRunWhenModify(): void
    {
        $this->repoProvider->put(Ref::ProviderAmazon);
        $this->repoBook->put(Ref::BookRomeoAndJuliet);

        $this->repoPurchase->put(Ref::PurchaseAmazonInv1);
        $line1 = $this->repoPurchaseLine->attach(Ref::PurchaseLineAmazonLine1);
        $this->repoPurchaseLine->attach(Ref::PurchaseLineAmazonLine2);

        $data = $this->getPayload('purchase');
        $data['lines'][0]['lineId'] = $line1->getId();
        $payload = new PurchaseUpdatePayload($data);

        $this->updater->dispatch('invalid-id', $payload);

        assertStored($this->repoPurchase);
        assertStored($this->repoPurchaseLine);
        assertRemoved($this->repoPurchaseLine);
        assertDispatch($this->bus, PurchaseUpdatedEvent::class);
    }
}
