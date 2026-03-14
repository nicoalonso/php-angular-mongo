<?php declare(strict_types=1);

namespace App\Domain\Identity\List;

use Doctrine\Common\Collections\ArrayCollection;

final class ListResult
{
    private ArrayCollection $items;
    private Pagination $pagination;

    public function __construct(
        ?ArrayCollection $items = null,
        ?Pagination $pagination = null,
    ) {
        $this->items = $items ?? new ArrayCollection();
        $this->pagination = $pagination ?? new Pagination($this->items->count());
    }

    public function items(): ArrayCollection
    {
        return $this->items;
    }

    public function pagination(): Pagination
    {
        return $this->pagination;
    }
}
