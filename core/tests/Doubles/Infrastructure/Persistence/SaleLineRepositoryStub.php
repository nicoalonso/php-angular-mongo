<?php declare(strict_types=1);

namespace App\Tests\Doubles\Infrastructure\Persistence;

use App\Domain\Sale\SaleLine;
use App\Domain\Sale\SaleLineCollection;
use App\Domain\Sale\SaleLineRepository;
use App\Tests\Fixtures\Mothers\SaleLineMother;
use App\Tests\Fixtures\Ref;

/**
 * @template-extends EntityRepositoryStub<SaleLine>
 */
final class SaleLineRepositoryStub extends EntityRepositoryStub implements SaleLineRepository
{
    public function __construct(
        private readonly ?SaleRepositoryStub $repoSale = null,
        private readonly ?BookRepositoryStub $repoBook = null,
    )
    {
        parent::__construct();
    }

    public function obtainBySale(string $saleId): SaleLineCollection
    {
        return new SaleLineCollection($this->list);
    }

    public function obtainByBook(string $bookId, ?int $limit = null): SaleLineCollection
    {
        return new SaleLineCollection($this->list);
    }

    protected function makeFixtures(): void
    {
        $johnDoeSale1 = $this->repoSale?->get(Ref::SaleJohnDoe1);
        $johnDoeSale2 = $this->repoSale?->get(Ref::SaleJohnDoe2);

        $romeoAndJuliet = $this->repoBook?->get(Ref::BookRomeoAndJuliet);
        $donQuijote = $this->repoBook?->get(Ref::BookDonQuijote);

        $saleLineJohnDoe1Line1 = SaleLineMother::johnSale1Line1(
            sale: $johnDoeSale1,
            book: $romeoAndJuliet,
        );
        $this->addFixture(Ref::SaleLineJohnDoe1Line1, $saleLineJohnDoe1Line1);

        $saleLineJohnDoe1Line2 = SaleLineMother::johnSale1Line2(
            sale: $johnDoeSale1,
            book: $donQuijote,
        );
        $this->addFixture(Ref::SaleLineJohnDoe1Line2, $saleLineJohnDoe1Line2);

        $saleLineJohnDoe2Line1 = SaleLineMother::johnSale2Line1(
            sale: $johnDoeSale2,
            book: $romeoAndJuliet,
        );
        $this->addFixture(Ref::SaleLineJohnDoe2Line1, $saleLineJohnDoe2Line1);
    }
}
