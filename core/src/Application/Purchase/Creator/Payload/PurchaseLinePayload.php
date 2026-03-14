<?php declare(strict_types=1);

namespace App\Application\Purchase\Creator\Payload;

use App\Domain\Identity\Payload;

final class PurchaseLinePayload extends Payload
{
    private string $lineId;
    private string $bookId;
    private int $quantity;
    private float $unitPrice;
    private float $discountPercentage;
    private float $total;

    public function __construct(array $payload)
    {
        parent::__construct($payload);

        $this->lineId = $this->data->toString('lineId');
        $this->bookId = $this->data->toString('bookId');
        $this->quantity = $this->data->toInt('quantity');
        $this->unitPrice = $this->data->toFloat('unitPrice');
        $this->discountPercentage = $this->data->toFloat('discountPercentage');
        $this->total = $this->data->toFloat('total');
    }

    public function getLineId(): string
    {
        return $this->lineId;
    }

    public function getBookId(): string
    {
        return $this->bookId;
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
