<?php

namespace App\Domain\Bus;

interface DomainBus
{
    public function dispatch(DomainEvent $event): void;
}