<?php declare(strict_types=1);

namespace App\Application\Sale\Creator\Payload;

use App\Domain\Identity\Payload;

final class SaleLinePayload extends Payload
{
    private string $lineId;
    private string $bookId;
    private int $quantity;
    private float $price;
    private float $discount;
    private float $total;

    public function __construct(array $payload)
    {
        parent::__construct($payload);

        $this->lineId = $this->data->toString('lineId');
        $this->bookId = $this->data->toString('bookId');
        $this->quantity = $this->data->toInt('quantity');
        $this->price = $this->data->toFloat('price');
        $this->discount = $this->data->toFloat('discount');
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
