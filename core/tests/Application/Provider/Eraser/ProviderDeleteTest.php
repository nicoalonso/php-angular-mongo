<?php

namespace App\Tests\Application\Provider\Eraser;

use App\Application\Provider\Eraser\ProviderAssociatedException;
use App\Application\Provider\Eraser\ProviderDelete;
use App\Domain\Provider\Exception\ProviderNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\ProviderRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\PurchaseRepositoryStub;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;

class ProviderDeleteTest extends TestCase
{
    private ProviderRepositoryStub $repoProvider;
    private PurchaseRepositoryStub $repoPurchase;
    private ProviderDelete $eraser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoProvider = new ProviderRepositoryStub();
        $this->repoPurchase = new PurchaseRepositoryStub();
        $this->eraser = new ProviderDelete(
            $this->repoProvider,
            $this->repoPurchase,
        );
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(ProviderNotFoundException::class);
        $this->eraser->dispatch('not-found-id');
    }

    public function testShouldFailWhenPurchaseRelated(): void
    {
        $this->repoProvider->put(Ref::ProviderAmazon);
        $this->repoPurchase->attach(Ref::PurchaseAmazonInv1);

        $this->expectException(ProviderAssociatedException::class);
        $this->eraser->dispatch('12345678');
    }

    public function testShouldRunWhenRemoved(): void
    {
        $this->repoProvider->put(Ref::ProviderAmazon);

        $this->eraser->dispatch('12345678');

        self::assertNotNull($this->repoProvider->removed);
    }
}
