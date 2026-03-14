<?php declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Domain\Bus\DomainBus;
use App\Domain\Bus\DomainEvent;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @codeCoverageIgnore
 */
final readonly class SymfonyDomainBus implements DomainBus
{
    public function __construct(private MessageBusInterface $messageBus) {}

    public function dispatch(DomainEvent $event): void
    {
        $stamps = [];
        if ($event->route()->has()) {
            $stamps[] = new AmqpStamp($event->route()->value);
        }

        $this->messageBus->dispatch($event, $stamps);
    }
}
