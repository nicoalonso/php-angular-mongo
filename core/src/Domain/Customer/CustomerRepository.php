<?php

namespace App\Domain\Customer;

use App\Domain\Identity\IdentityRepository;

/**
 * @template-extends IdentityRepository<Customer>
 */
interface CustomerRepository extends IdentityRepository
{
    public function obtainByName(string $name, string $surname): ?Customer;
    public function obtainByNumber(string $number): ?Customer;
}
