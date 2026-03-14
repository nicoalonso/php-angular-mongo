<?php declare(strict_types=1);

namespace App\Domain\Services\BookInspector;

use App\Domain\Borrow\BorrowLineRepository;

/**
 * Apply State pattern using a Factory
 */
final readonly class BookInspectFactory
{
    public function __construct(
        private BorrowLineRepository $repoLineBorrow
    ) {}

    public function create(bool $isSale): BookInspector
    {
        return match ($isSale) {
            true => new BookSaleInspect($this->repoLineBorrow),
            default => new BookBorrowInspect($this->repoLineBorrow)
        };
    }
}
