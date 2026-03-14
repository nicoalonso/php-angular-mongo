<?php declare(strict_types=1);

namespace App\Domain\Provider;

readonly class ProviderDescriptor
{
    public function __construct(
        public string $id,
        public string $name,
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
