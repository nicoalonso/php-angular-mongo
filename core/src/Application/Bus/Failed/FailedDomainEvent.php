<?php declare(strict_types=1);

namespace App\Application\Bus\Failed;

final readonly class FailedDomainEvent
{
    public function __construct(
        private string $action,
        private array $body,
    ) {}

    public function action(): string
    {
        return $this->action;
    }

    public function body(): array
    {
        return $this->body;
    }
}