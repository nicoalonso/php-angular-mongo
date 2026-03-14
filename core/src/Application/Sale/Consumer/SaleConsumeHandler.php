<?php declare(strict_types=1);

namespace App\Application\Sale\Consumer;

use App\Application\Sale\Creator\SaleCreatedEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

final readonly class SaleConsumeHandler
{
    public function __construct(private SaleConsume $consumer) {}

    #[AsMessageHandler]
    public function handleCreated(SaleCreatedEvent $event): void
    {
        $this->consumer->dispatch($event->getBooks());
    }
}
