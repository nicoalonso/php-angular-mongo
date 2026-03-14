<?php declare(strict_types=1);

namespace App\Domain\Identity;

use DateTimeImmutable;

abstract class Entity extends Identity
{
    protected string $createdBy;
    protected ?string $updatedBy = null;
    protected DateTimeImmutable $createdAt;
    protected ?DateTimeImmutable $updatedAt = null;

    public function __construct(string $createdBy, ?string $id = null)
    {
        parent::__construct($id);

        $this->createdBy = $createdBy;
        $this->updatedBy = null;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = null;
    }

    public function updated(string $updatedBy): void
    {
        $this->updatedBy = $updatedBy;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getCreatedBy(): string
    {
        return $this->createdBy;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
