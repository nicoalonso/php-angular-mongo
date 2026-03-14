<?php declare(strict_types=1);

namespace App\Application\Borrow\Reader;

use App\Domain\Borrow\BorrowLineRepository;
use App\Domain\Borrow\BorrowRepository;
use App\Domain\Borrow\Exception\BorrowNotFoundException;

final readonly class BorrowRead
{
    public function __construct(
        private BorrowRepository $repoBorrow,
        private BorrowLineRepository $repoBorrowLine,
    ) {}

    public function dispatch(string $borrowId): BorrowDecorator
    {
        $borrow = $this->repoBorrow->obtainById($borrowId);
        if (null === $borrow) {
            throw new BorrowNotFoundException();
        }

        $lines = $this->repoBorrowLine->obtainByBorrow($borrowId);

        return new BorrowDecorator($borrow, $lines);
    }
}
