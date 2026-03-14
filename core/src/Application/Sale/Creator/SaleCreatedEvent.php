<?php declare(strict_types=1);

namespace App\Application\Sale\Creator;

use App\Domain\Book\BookDescriptor;
use App\Domain\Bus\DomainEvent;
use App\Domain\Sale\Sale;

final class SaleCreatedEvent extends DomainEvent
{
    public const string ACTION = 'sale.created';
    private const string TYPE = 'sale';

    /**
     * @param Sale $sale
     * @param BookDescriptor[] $books
     */
    public function __construct(
        private readonly Sale  $sale,
        private readonly array $books,
    )
    {
        parent::__construct(self::ACTION, self::TYPE);
    }

    public function getSale(): Sale
    {
        return $this->sale;
    }

    /**
     * @return BookDescriptor[]
     */
    public function getBooks(): array
    {
        return $this->books;
    }
}
