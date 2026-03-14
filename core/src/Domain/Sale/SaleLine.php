<?php declare(strict_types=1);

namespace App\Domain\Sale;

use App\Domain\Book\Book;
use App\Domain\Book\BookDescriptor;
use App\Domain\Identity\Identity;

class SaleLine extends Identity
{
    private Sale $sale;
    private BookDescriptor $book;
    private int $quantity;
    private float $price;
    private float $discount;
    private float $total;

    public function __construct(
        Sale $sale,
        Book $book,
        int $quantity,
        float $price,
        float $discount,
        float $total,
    )
    {
        parent::__construct();

        $this->sale = $sale;
        $this->book = $book->getDescriptor();
        $this->quantity = $quantity;
        $this->price = $price;
        $this->discount = $discount;
        $this->total = $total;
    }

    public function modify(
        Book $book,
        int $quantity,
        float $price,
        float $discount,
        float $total,
    ): void
    {
        $this->book = $book->getDescriptor();
        $this->quantity = $quantity;
        $this->price = $price;
        $this->discount = $discount;
        $this->total = $total;
    }

    public function getSale(): Sale
    {
        return $this->sale;
    }

    public function getBook(): BookDescriptor
    {
        return $this->book;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function getTotal(): float
    {
        return $this->total;
    }
}
