<?php declare(strict_types=1);

namespace App\Domain\Identity\List;

final readonly class Pagination
{
    public function __construct(
        private int $total = 0,
        private int $page = 1,
        private int $rowsPerPage = 10,
    ) {}

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getRowsPerPage(): int
    {
        return $this->rowsPerPage;
    }

    public function getTotalPages(): int
    {
        if ($this->total <= 0 || $this->rowsPerPage <= 0) {
            return 0;
        }

        return (int) ceil($this->total / $this->rowsPerPage);
    }
}
