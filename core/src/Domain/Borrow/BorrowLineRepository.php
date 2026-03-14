<?php

namespace App\Domain\Borrow;

use App\Domain\Identity\IdentityRepository;

/**
 * @template-extends IdentityRepository<BorrowLine>
 */
interface BorrowLineRepository extends IdentityRepository
{
    public function obtainByBorrow(string $borrowId): BorrowLineCollection;
    public function obtainByBook(string $bookId, ?int $limit = null): BorrowLineCollection;
    public function obtainActiveByBook(string $bookId): BorrowLineCollection;
}
