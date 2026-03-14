<?php declare(strict_types=1);

namespace App\Domain\Book;

readonly class BookSale
{
    public function __construct(
        private bool $saleable,
        private float $price,
        private float $discount,
    ) {}

    public function isSaleable(): bool
    {
        return $this->saleable;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }
}
