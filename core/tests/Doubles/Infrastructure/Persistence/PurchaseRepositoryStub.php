<?php declare(strict_types=1);

namespace App\Tests\Doubles\Infrastructure\Persistence;

use App\Domain\Purchase\Purchase;
use App\Domain\Purchase\PurchaseCollection;
use App\Domain\Purchase\PurchaseRepository;
use App\Tests\Fixtures\Mothers\PurchaseMother;
use App\Tests\Fixtures\Ref;

/**
 * @template-extends EntityRepositoryStub<Purchase>
 */
final class PurchaseRepositoryStub extends EntityRepositoryStub implements PurchaseRepository
{
    public function __construct(
        private readonly ?ProviderRepositoryStub $repoProvider = null,
    )
    {
        parent::__construct();
    }

    public function obtainByProviderAndNumber(string $providerId, string $invoiceNumber): ?Purchase
    {
        return $this->read;
    }

    public function obtainByProvider(string $providerId, ?int $limit = null): PurchaseCollection
    {
        return new PurchaseCollection($this->list);
    }

    protected function makeFixtures(): void
    {
        $amazon = $this->repoProvider?->get(Ref::ProviderAmazon);
        $bestBuy = $this->repoProvider?->get(Ref::ProviderBestBuy);

        $purchaseAmazonInv1 = PurchaseMother::amazonInv1(provider: $amazon);
        $this->addFixture(Ref::PurchaseAmazonInv1, $purchaseAmazonInv1);

        $purchaseBestBuyInv2 = PurchaseMother::bestBuyInv2(provider: $bestBuy);
        $this->addFixture(Ref::PurchaseBestBuyInv2, $purchaseBestBuyInv2);
    }
}
