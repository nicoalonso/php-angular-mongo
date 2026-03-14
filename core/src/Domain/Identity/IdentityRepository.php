<?php

namespace App\Domain\Identity;

use App\Domain\Identity\List\ListQuery;
use App\Domain\Identity\List\ListResult;

/**
 * @template T
 * @psalm-template T
 */
interface IdentityRepository
{
    /** @return T | null */
    public function obtainById(string $id);
    /** @param T $entity */
    public function save($entity): void;
    /** @param T $entity */
    public function remove($entity): void;
    public function obtainByQuery(ListQuery $query): ListResult;
}
