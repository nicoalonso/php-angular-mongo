<?php declare(strict_types=1);

namespace App\Infrastructure\Serializer\Messenger;

use App\Application\Book\Inventory\BookInventoryEvent;
use App\Application\Borrow\Sanctioner\BorrowPenaltyEvent;
use App\Application\Bus\Failed\FailedDomainEvent;
use App\Domain\Identity\Valet;
use App\Infrastructure\Serializer\Messenger\Book\BookCommand;
use App\Infrastructure\Serializer\Messenger\Book\BookInventorySerialize;
use App\Infrastructure\Serializer\Messenger\Identity\EmptyMessageSerializer;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

final class LibrarySerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        $body = json_decode($encodedEnvelope['body'], true);
        if (null === $body) {
            throw new MessageDecodingFailedException('Invalid body');
        }

        $data = new Valet($body);
        $action = $data->toString('action', 'unknown');

        $message = match ($action) {
            BookInventoryEvent::ACTION => BookCommand::inventory($data),
            // Borrow
            BorrowPenaltyEvent::ACTION => new BorrowPenaltyEvent(),
            // Fail
            default => new FailedDomainEvent($action, $body),
        };

        return new Envelope($message);
    }

    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();

        $body = match ($message::class) {
            BookInventoryEvent::class => new BookInventorySerialize($message),
            // Borrow
            BorrowPenaltyEvent::class => new EmptyMessageSerializer($message),
            // Fail
            default => throw new MessageDecodingFailedException('Unsupported message class: ' . $message::class),
        };

        return [
            'body' => json_encode($body),
            'headers' => [],
        ];
    }
}
