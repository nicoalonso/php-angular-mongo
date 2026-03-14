<?php declare(strict_types=1);

namespace App\Infrastructure\Serializer\Messenger\Identity;

use App\Domain\Bus\DomainEvent;
use JsonSerializable;

final readonly class EmptyMessageSerializer implements JsonSerializable
{
    public function __construct(private DomainEvent $message) {}

    public function jsonSerialize(): array
    {
        return [
            'action' => $this->message->action(),
            'type' => $this->message->type(),
        ];
    }
}
