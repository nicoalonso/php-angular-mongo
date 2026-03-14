<?php

namespace App\Domain\Borrow;

use App\Domain\Identity\IdentityRepository;

/**
 * @template-extends IdentityRepository<Borrow>
 */
interface BorrowRepository extends IdentityRepository
{
    public function obtainByNumber(string $number): ?Borrow;
    public function obtainByCustomer(string $customerId, ?int $limit = null): BorrowCollection;
    public function obtainByOverdue(): BorrowCollection;
}
