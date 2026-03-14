<?php declare(strict_types=1);

namespace App\Domain\Editorial;

use App\Domain\Identity\IdentityRepository;

/**
 * @template-extends IdentityRepository<Editorial>
 */
interface EditorialRepository extends IdentityRepository
{
    public function obtainByName(string $name): ?Editorial;
}
