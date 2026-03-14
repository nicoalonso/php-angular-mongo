<?php declare(strict_types=1);

namespace App\Domain\Purchase;

use App\Domain\Book\Book;
use App\Domain\Book\BookDescriptor;
use App\Domain\Identity\Identity;

class PurchaseLine extends Identity
{
    private Purchase $purchase;
    private BookDescriptor $book;
    private int $quantity;
    private float $unitPrice;
    private float $discountPercentage;
    private float $total;

     public function __construct(
         Purchase $purchase,
         Book $book,
         int $quantity,
         float $unitPrice,
         float $discountPercentage,
         float $total,
     )
     {
         parent::__construct();

         $this->purchase = $purchase;
         $this->book = $book->getDescriptor();
         $this->quantity = $quantity;
         $this->unitPrice = $unitPrice;
         $this->discountPercentage = $discountPercentage;
         $this->total = $total;
     }

    public function modify(
        Book $book,
        int $quantity,
        float $unitPrice,
        float $discountPercentage,
        float $total,
    ): void
    {
        $this->book = $book->getDescriptor();
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
        $this->discountPercentage = $discountPercentage;
        $this->total = $total;
    }

    public function getPurchase(): Purchase
    {
        return $this->purchase;
    }

     public function getBook(): BookDescriptor
     {
         return $this->book;
     }

     public function getQuantity(): int
     {
         return $this->quantity;
     }

     public function getUnitPrice(): float
     {
         return $this->unitPrice;
     }

    public function getDiscountPercentage(): float
    {
        return $this->discountPercentage;
    }

    public function getTotal(): float
    {
        return $this->total;
    }
}
