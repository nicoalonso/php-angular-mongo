<?php

namespace App\Domain\Provider;

use App\Domain\Identity\IdentityRepository;

/**
 * @template-extends IdentityRepository<Provider>
 */
interface ProviderRepository extends IdentityRepository
{
    public function obtainByName(string $name): ?Provider;
}
