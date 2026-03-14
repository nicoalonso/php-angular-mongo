<?php declare(strict_types=1);

namespace App\Domain\Customer;

readonly class CustomerDescriptor
{
    public function __construct(
        private string $id,
        private string $name,
        private string $surname,
        private string $vatNumber,
        private string $number,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getVatNumber(): string
    {
        return $this->vatNumber;
    }

    public function getNumber(): string
    {
        return $this->number;
    }
}
