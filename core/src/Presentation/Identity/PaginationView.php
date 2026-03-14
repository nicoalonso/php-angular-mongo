<?php declare(strict_types=1);

namespace App\Presentation\Identity;

use App\Domain\Identity\List\Pagination;
use JsonSerializable;

final readonly class PaginationView implements JsonSerializable
{
    public function __construct(private Pagination $pagination) {}

    public function jsonSerialize(): array
    {
        return [
            'total' => $this->pagination->getTotal(),
            'rowsPerPage' => $this->pagination->getRowsPerPage(),
            'page' => $this->pagination->getPage(),
            'totalPages' => $this->pagination->getTotalPages(),
        ];
    }
}
