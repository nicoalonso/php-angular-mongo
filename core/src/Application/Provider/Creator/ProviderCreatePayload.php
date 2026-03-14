<?php declare(strict_types=1);

namespace App\Application\Provider\Creator;

use App\Application\Identity\Payload\AddressPayload;
use App\Application\Identity\Payload\EnterpriseContactPayload;
use App\Domain\Identity\Payload;

class ProviderCreatePayload extends Payload
{
    private string $comercialName;
    private string $vatNumber;
    private EnterpriseContactPayload $contact;
    private AddressPayload $address;

    public function __construct(array $payload)
    {
        parent::__construct($payload);

        $this->comercialName = $this->data->toString('comercialName');
        $this->vatNumber = $this->data->toString('vatNumber');
        $this->contact = new EnterpriseContactPayload($this->data->toArray('contact'));
        $this->address = new AddressPayload($this->data->toArray('address'));
    }

    public function getComercialName(): string
    {
        return $this->comercialName;
    }

    public function getVatNumber(): string
    {
        return $this->vatNumber;
    }

    public function getContact(): EnterpriseContactPayload
    {
        return $this->contact;
    }

    public function getAddress(): AddressPayload
    {
        return $this->address;
    }
}
