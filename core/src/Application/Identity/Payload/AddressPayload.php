<?php declare(strict_types=1);

namespace App\Application\Identity\Payload;

use App\Domain\Identity\Payload;

final class AddressPayload extends Payload
{
    private string $street;
    private string $postalCode;
    private string $city;
    private string $province;
    private string $country;

    public function __construct(array $payload)
    {
        parent::__construct($payload);

        $this->street = $this->data->toString('street');
        $this->postalCode = $this->data->toString('postalCode');
        $this->city = $this->data->toString('city');
        $this->province = $this->data->toString('province');
        $this->country = $this->data->toString('country');
    }

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
