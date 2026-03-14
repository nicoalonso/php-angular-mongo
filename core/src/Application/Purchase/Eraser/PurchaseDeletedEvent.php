<?php declare(strict_types=1);

namespace App\Application\Purchase\Eraser;

use App\Domain\Book\BookDescriptor;
use App\Domain\Bus\DomainEvent;
use App\Domain\Purchase\Purchase;

final class PurchaseDeletedEvent extends DomainEvent
{
    public const string ACTION = 'purchase.deleted';
    private const string TYPE = 'purchase';

    /**
     * @param Purchase $purchase
     * @param BookDescriptor[] $books
     */
    public function __construct(
        private readonly Purchase $purchase,
        private readonly array $books,
    )
    {
        parent::__construct(self::ACTION, self::TYPE);
    }

    public function getPurchase(): Purchase
    {
        return $this->purchase;
    }

    /**
     * @return BookDescriptor[]
     */
    public function getBooks(): array
    {
        return $this->books;
    }
}
