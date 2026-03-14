<?php declare(strict_types=1);

namespace App\Domain\Purchase;

use App\Domain\Purchase\Exception\InvalidPurchaseInvoiceNumberException;

readonly class PurchaseInvoice
{
    public function __construct(
        private string $number,
        private float $amount,
        private float $taxes,
        private float $total,
    )
    {
        $this->check($this->number);
    }

    private function check(string $number): void
    {
        if (empty($number)) {
            throw new InvalidPurchaseInvoiceNumberException();
        }
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getTaxes(): float
    {
        return $this->taxes;
    }

    public function getTotal(): float
    {
        return $this->total;
    }
}