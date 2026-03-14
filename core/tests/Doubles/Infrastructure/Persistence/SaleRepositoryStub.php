<?php declare(strict_types=1);

namespace App\Tests\Doubles\Infrastructure\Persistence;

use App\Domain\Sale\Sale;
use App\Domain\Sale\SaleCollection;
use App\Domain\Sale\SaleRepository;
use App\Tests\Fixtures\Mothers\SaleMother;
use App\Tests\Fixtures\Ref;

/**
 * @template-extends EntityRepositoryStub<Sale>
 */
final class SaleRepositoryStub extends EntityRepositoryStub implements SaleRepository
{
    public function __construct(
        private readonly ?CustomerRepositoryStub $repoCustomer = null,
    )
    {
        parent::__construct();
    }

    public function obtainByNumber(string $number): ?Sale
    {
        return $this->read;
    }

    public function obtainByCustomer(string $customerId, ?int $limit = null): SaleCollection
    {
        return new SaleCollection($this->list);
    }

    protected function makeFixtures(): void
    {
        $johnDoe = $this->repoCustomer?->get(Ref::CustomerJohnDoe);

        $johnDoeSale1 = SaleMother::johnDoeSale1(customer: $johnDoe);
        $this->addFixture(Ref::SaleJohnDoe1, $johnDoeSale1);

        $johnDoeSale2 = SaleMother::johnDoeSale2(customer: $johnDoe);
        $this->addFixture(Ref::SaleJohnDoe2, $johnDoeSale2);
    }
}
