<?php declare(strict_types=1);

namespace App\Domain\Identity\List;

interface FilterVisitor
{
    public function visit(FilterField $field, mixed $builder): bool;
}