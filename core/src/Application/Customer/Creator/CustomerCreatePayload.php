<?php declare(strict_types=1);

namespace App\Application\Customer\Creator;

use App\Application\Customer\Creator\Payload\ContactPayload;
use App\Application\Identity\Payload\AddressPayload;
use App\Domain\Identity\Payload;

class CustomerCreatePayload extends Payload
{
    private string $surname;
    private ContactPayload $contact;
    private AddressPayload $address;
    private ?string $vatNumber;

    public function __construct(array $payload)
    {
        parent::__construct($payload);

        $this->surname = $this->data->toString('surname');
        $this->contact = new ContactPayload($this->data->toArray('contact'));
        $this->address = new AddressPayload($this->data->toArray('address'));
        $this->vatNumber = $this->data->toString('vatNumber');
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getContact(): ContactPayload
    {
        return $this->contact;
    }

    public function getAddress(): AddressPayload
    {
        return $this->address;
    }

    public function getVatNumber(): ?string
    {
        return $this->vatNumber;
    }
}
