<?php declare(strict_types=1);

namespace App\Application\Sale\Creator\Payload;

use App\Domain\Identity\Payload;
use DateTimeImmutable;

final class SaleInvoicePayload extends Payload
{
    private ?DateTimeImmutable $date;
    private float $amount;
    private float $taxPercentage;
    private float $taxes;
    private float $total;

    public function __construct(array $payload)
    {
        parent::__construct($payload);

        $this->date = $this->data->toDateImmutable('date', DATE_SHORT);
        $this->amount = $this->data->toFloat('amount');
        $this->taxPercentage = $this->data->toFloat('taxPercentage');
        $this->taxes = $this->data->toFloat('taxes');
        $this->total = $this->data->toFloat('total');
    }

    public function getDate(): ?DateTimeImmutable
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
