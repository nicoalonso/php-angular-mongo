<?php

namespace App\Domain\Author;

use App\Domain\Identity\IdentityRepository;

/**
 * @template-extends IdentityRepository<Author>
 */
interface AuthorRepository extends IdentityRepository
{
    public function obtainByName(string $name): ?Author;
}
