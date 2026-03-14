<?php declare(strict_types=1);

namespace App\Domain\Bus;

abstract class DomainEvent
{
    public function __construct(
        private readonly string      $action,
        private readonly string      $type,
        private readonly DomainRoute $route = DomainRoute::NONE,
    ) {}

    public function action(): string
    {
        return $this->action;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function route(): DomainRoute
    {
        return $this->route;
    }
}
