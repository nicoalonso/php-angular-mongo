<?php declare(strict_types=1);

namespace App\Application\Book\Creator\Payload;

use App\Domain\Identity\Payload;

final class BookSalePayload extends Payload
{
    private bool $saleable;
    private float $price;
    private float $discount;

    public function __construct(array $payload)
    {
        parent::__construct($payload);

        $this->saleable = $this->data->toBool('saleable');
        $this->price = $this->data->toFloat('price');
        $this->discount = $this->data->toFloat('discount');
    }

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
