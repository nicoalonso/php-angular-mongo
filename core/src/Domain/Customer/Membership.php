<?php declare(strict_types=1);

namespace App\Domain\Customer;

use DateTimeImmutable;

class Membership
{
    private string $number;
    private bool $active;
    private ?DateTimeImmutable $endedAt;

    public function __construct(string $number)
    {
        $this->number = $number;
        $this->enable();
    }

    public function enable(): void
    {
        $this->active = true;
        $this->endedAt = null;
    }

    public function disable(): void
    {
        $this->active = false;
        $this->endedAt = new DateTimeImmutable();
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getEndedAt(): ?DateTimeImmutable
    {
        return $this->endedAt;
    }
}
