<?php declare(strict_types=1);

namespace App\Domain\Editorial;

use App\Domain\Common\Address;
use App\Domain\Common\EnterpriseContact;
use App\Domain\Identity\Entity;
use App\Domain\Identity\Exception\NameEmptyException;

class Editorial extends Entity
{
    private string $name;
    private string $comercialName;
    private EnterpriseContact $contact;
    private Address $address;

    public function __construct(
        string $name,
        string $comercialName,
        EnterpriseContact $contact,
        Address $address,
        string $createdBy,
    )
    {
        parent::__construct($createdBy);
        $this->check($name);

        $this->name = $name;
        $this->comercialName = $comercialName;
        $this->contact = $contact;
        $this->address = $address;
    }

    public function modify(
        string $name,
        string $comercialName,
        EnterpriseContact $contact,
        Address $address,
        string $updatedBy,
    ): void
    {
        $this->check($name);

        $this->name = $name;
        $this->comercialName = $comercialName;
        $this->contact = $contact;
        $this->address = $address;
        $this->updated($updatedBy);
    }

    private function check(string $name): void
    {
        if (empty($name)) {
            throw new NameEmptyException();
        }
    }

    public function getDescriptor(): EditorialDescriptor
    {
        return new EditorialDescriptor($this->id, $this->name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getComercialName(): string
    {
        return $this->comercialName;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getContact(): EnterpriseContact
    {
        return $this->contact;
    }
}
