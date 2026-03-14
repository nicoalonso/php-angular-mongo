<?php declare(strict_types=1);

namespace App\Tests\Doubles\Infrastructure\Persistence;

use App\Domain\Borrow\BorrowLine;
use App\Domain\Borrow\BorrowLineCollection;
use App\Domain\Borrow\BorrowLineRepository;
use App\Tests\Fixtures\Mothers\BorrowLineMother;
use App\Tests\Fixtures\Ref;

/**
 * @template-extends EntityRepositoryStub<BorrowLine>
 */
final class BorrowLineRepositoryStub extends EntityRepositoryStub implements BorrowLineRepository
{
    public function __construct(
        private readonly ?BorrowRepositoryStub $repoBorrow = null,
        private readonly ?BookRepositoryStub   $repoBook = null,
    )
    {
        parent::__construct();
    }

    public function obtainByBorrow(string $borrowId): BorrowLineCollection
    {
        return new BorrowLineCollection($this->list);
    }

    public function obtainByBook(string $bookId, ?int $limit = null): BorrowLineCollection
    {
        return new BorrowLineCollection($this->list);
    }

    public function obtainActiveByBook(string $bookId): BorrowLineCollection
    {
        return new BorrowLineCollection($this->list);
    }

    protected function makeFixtures(): void
    {
        $borrowJohnDoe = $this->repoBorrow?->get(Ref::BorrowJohnDoe);
        $bookRomeoAndJuliet = $this->repoBook?->get(Ref::BookRomeoAndJuliet);
        $bookQuijote = $this->repoBook?->get(Ref::BookDonQuijote);

        $borrowJohnRomeoAndJuliet = BorrowLineMother::romeoAndJuliet(
            borrow: $borrowJohnDoe,
            book: $bookRomeoAndJuliet,
        );
        $this->addFixture(Ref::BorrowLineJohnRomeoAndJuliet, $borrowJohnRomeoAndJuliet);

        $borrowJohnQuijote = BorrowLineMother::romeoAndJuliet(
            borrow: $borrowJohnDoe,
            book: $bookQuijote,
        );
        $this->addFixture(Ref::BorrowLineJohnQuijote, $borrowJohnQuijote);
    }
}
