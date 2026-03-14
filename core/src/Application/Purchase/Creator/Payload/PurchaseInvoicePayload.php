<?php declare(strict_types=1);

namespace App\Application\Purchase\Creator\Payload;

use App\Domain\Identity\Payload;

final class PurchaseInvoicePayload extends Payload
{
    private string $number;
    private float $amount;
    private float $taxes;
    private float $total;

    public function __construct(array $payload)
    {
        parent::__construct($payload);

        $this->number = $this->data->toString('number');
        $this->amount = $this->data->toFloat('amount');
        $this->taxes = $this->data->toFloat('taxes');
        $this->total = $this->data->toFloat('total');
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
