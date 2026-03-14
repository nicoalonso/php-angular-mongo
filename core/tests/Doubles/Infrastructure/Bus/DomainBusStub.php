<?php declare(strict_types=1);

namespace App\Tests\Doubles\Infrastructure\Bus;

use App\Domain\Bus\DomainBus;
use App\Domain\Bus\DomainEvent;
use App\Tests\Doubles\Exceptionable;
use App\Tests\Doubles\Spyable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

final class DomainBusStub implements DomainBus
{
    use Spyable;
    use Exceptionable;

    /** @var Collection<DomainEvent> */
    public Collection $events;
    public ?DomainEvent $lastEvent = null;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function dispatch(DomainEvent $event): void
    {
        $this->spy();
        $this->throw();

        $this->lastEvent = $event;
        $this->events->add($event);
    }

    public function find(string $eventClass): ?DomainEvent
    {
        return $this->events->findFirst(fn($key, $event) => $event instanceof $eventClass);
    }
}

function assertDispatch(DomainBusStub $bus, string $eventClass): void
{
    $eventExists = $bus->events->exists(fn($key, $event) => $event instanceof $eventClass);
    assertTrue($eventExists, "Event of type $eventClass was not dispatched");
}

function assertNotDispatch(DomainBusStub $bus, string $eventClass): void
{
    $eventExists = $bus->events->exists(fn($key, $event) => $event instanceof $eventClass);
    assertFalse($eventExists, "Event of type $eventClass was dispatched");
}
