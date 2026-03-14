<?php declare(strict_types=1);

namespace App\Domain\Sale;

use App\Domain\Sale\Exception\InvalidSaleDateException;
use DateTimeImmutable;

readonly class SaleInvoice
{
    public function __construct(
        private DateTimeImmutable $date,
        private float $amount,
        private float $taxPercentage,
        private float $taxes,
        private float $total,
    ) {
        $this->check($this->date);
    }

    private function check(DateTimeImmutable $date): void
    {
        $now = new DateTimeImmutable('today midnight +1 day');
        if ($date > $now) {
            throw new InvalidSaleDateException();
        }
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getTaxPercentage(): float
    {
        return $this->taxPercentage;
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
