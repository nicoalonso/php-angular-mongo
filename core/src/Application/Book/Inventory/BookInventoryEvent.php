<?php declare(strict_types=1);

namespace App\Application\Book\Inventory;

use App\Domain\Book\BookDescriptor;
use App\Domain\Bus\DomainEvent;
use App\Domain\Bus\DomainRoute;

final class BookInventoryEvent extends DomainEvent
{
    public const string ACTION = 'book.inventory';
    private const string TYPE = 'book';

    public function __construct(private readonly BookDescriptor $descriptor)
    {
        parent::__construct(self::ACTION, self::TYPE, DomainRoute::LIBRARY);
    }

    public function getDescriptor(): BookDescriptor
    {
        return $this->descriptor;
    }
}
