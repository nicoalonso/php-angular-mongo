<?php

namespace App\Domain\Sale;

use App\Domain\Identity\IdentityRepository;

/**
 * @template-extends IdentityRepository<Sale>
 */
interface SaleRepository extends IdentityRepository
{
    public function obtainByNumber(string $number): ?Sale;
    public function obtainByCustomer(string $customerId, ?int $limit = null): SaleCollection;
}
