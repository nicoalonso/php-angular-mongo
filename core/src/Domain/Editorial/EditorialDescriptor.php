<?php declare(strict_types=1);

namespace App\Domain\Editorial;

readonly class EditorialDescriptor
{
    public function __construct(
        private string $id,
        private string $name,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
