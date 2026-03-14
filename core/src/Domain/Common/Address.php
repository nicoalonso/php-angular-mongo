<?php declare(strict_types=1);

namespace App\Domain\Common;

final readonly class Address
{
    public function __construct(
        private string $street,
        private string $postalCode,
        private string $city,
        private string $province,
        private string $country,
    ) {}

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getProvince(): string
    {
        return $this->province;
    }

    public function getCountry(): string
    {
        return $this->country;
    }
}