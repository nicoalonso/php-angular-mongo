<?php declare(strict_types=1);

namespace App\Infrastructure\Serializer\Messenger\Book;

use App\Application\Book\Inventory\BookInventoryEvent;
use JsonSerializable;

final readonly class BookInventorySerialize implements JsonSerializable
{
    public function __construct(private BookInventoryEvent $message) {}

    public function jsonSerialize(): array
    {
        return [
            'action' => $this->message->action(),
            'type' => $this->message->type(),
            'book' => [
                'id' => $this->message->getDescriptor()->getId(),
                'title' => $this->message->getDescriptor()->getTitle(),
                'isb' => $this->message->getDescriptor()->getIsbn(),
            ],
        ];
    }
}
