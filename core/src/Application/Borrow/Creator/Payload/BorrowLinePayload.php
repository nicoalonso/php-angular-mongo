<?php declare(strict_types=1);

namespace App\Application\Borrow\Creator\Payload;

use App\Domain\Identity\Payload;

final class BorrowLinePayload extends Payload
{
    private string $lineId;
    private string $bookId;
    private bool $returned;

    public function __construct(array $payload)
    {
        parent::__construct($payload);

        $this->lineId = $this->data->toString('lineId');
        $this->bookId = $this->data->toString('bookId');
        $this->returned = $this->data->toBool('returned');
    }

    public function getLineId(): string
    {
        return $this->lineId;
    }

    public function getBookId(): string
    {
        return $this->bookId;
    }

    public function isReturned(): bool
    {
        return $this->returned;
    }
}
