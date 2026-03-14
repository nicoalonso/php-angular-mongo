<?php declare(strict_types=1);

namespace App\Tests\Doubles\Infrastructure\Persistence;

use App\Domain\Purchase\PurchaseLine;
use App\Domain\Purchase\PurchaseLineCollection;
use App\Domain\Purchase\PurchaseLineRepository;
use App\Tests\Fixtures\Mothers\PurchaseLineMother;
use App\Tests\Fixtures\Ref;

/**
 * @template-extends EntityRepositoryStub<PurchaseLine>
 */
final class PurchaseLineRepositoryStub extends EntityRepositoryStub implements PurchaseLineRepository
{
    public function __construct(
        private readonly ?PurchaseRepositoryStub $repoPurchase = null,
        private readonly ?BookRepositoryStub     $repoBook = null,
    )
    {
        parent::__construct();
    }

    public function obtainByPurchase(string $purchaseId): PurchaseLineCollection
    {
        return new PurchaseLineCollection($this->list);
    }

    public function obtainByBook(string $bookId, ?int $limit = null): PurchaseLineCollection
    {
        return new PurchaseLineCollection($this->list);
    }

    protected function makeFixtures(): void
    {
        $purchaseAmazonInv1 = $this->repoPurchase?->get(Ref::PurchaseAmazonInv1);
        $purchaseBestBuyInv2 = $this->repoPurchase?->get(Ref::PurchaseBestBuyInv2);

        $bookRomeoAndJuliet = $this->repoBook?->get(Ref::BookRomeoAndJuliet);
        $bookDonQuijote = $this->repoBook?->get(Ref::BookDonQuijote);

        $amazonLine1 = PurchaseLineMother::amazonLine1(
            purchase: $purchaseAmazonInv1,
            book: $bookRomeoAndJuliet,
        );
        $this->addFixture(Ref::PurchaseLineAmazonLine1, $amazonLine1);

        $amazonLine2 = PurchaseLineMother::amazonLine2(
            purchase: $purchaseAmazonInv1,
            book: $bookDonQuijote,
        );
        $this->addFixture(Ref::PurchaseLineAmazonLine2, $amazonLine2);

        $bestBuyLine1 = PurchaseLineMother::bestBuyLine1(
            purchase: $purchaseBestBuyInv2,
            book: $bookRomeoAndJuliet,
        );
        $this->addFixture(Ref::PurchaseLineBestBuyLine1, $bestBuyLine1);
    }
}
