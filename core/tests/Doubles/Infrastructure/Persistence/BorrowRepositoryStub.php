<?php declare(strict_types=1);

namespace App\Tests\Doubles\Infrastructure\Persistence;

use App\Domain\Borrow\Borrow;
use App\Domain\Borrow\BorrowCollection;
use App\Domain\Borrow\BorrowRepository;
use App\Tests\Fixtures\Mothers\BorrowMother;
use App\Tests\Fixtures\Ref;

/**
 * @template-extends EntityRepositoryStub<Borrow>
 */
final class BorrowRepositoryStub extends EntityRepositoryStub implements BorrowRepository
{
    public function __construct(
        private readonly ?CustomerRepositoryStub $repoCustomer = null,
    )
    {
        parent::__construct();
    }

    public function obtainByNumber(string $number): ?Borrow
    {
        return $this->read;
    }

    public function obtainByCustomer(string $customerId, ?int $limit = null): BorrowCollection
    {
        return new BorrowCollection($this->list);
    }

    public function obtainByOverdue(): BorrowCollection
    {
        return new BorrowCollection($this->list);
    }

    protected function makeFixtures(): void
    {
        $johnDoe = $this->repoCustomer?->get(Ref::CustomerJohnDoe);

        $borrowJohnDoe = BorrowMother::johnDoe(customer: $johnDoe);
        $this->addFixture(Ref::BorrowJohnDoe, $borrowJohnDoe);
    }
}
