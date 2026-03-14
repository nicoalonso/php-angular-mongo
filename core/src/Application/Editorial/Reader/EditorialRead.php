<?php declare(strict_types=1);

namespace App\Application\Editorial\Reader;

use App\Domain\Editorial\Editorial;
use App\Domain\Editorial\EditorialRepository;
use App\Domain\Editorial\Exception\EditorialNotFoundException;

final readonly class EditorialRead
{
    public function __construct(private EditorialRepository $repoEditorial) {}

    public function dispatch(string $editorialId): Editorial
    {
        $editorial = $this->repoEditorial->obtainById($editorialId);
        if (null === $editorial) {
            throw new EditorialNotFoundException();
        }

        return $editorial;
    }
}
