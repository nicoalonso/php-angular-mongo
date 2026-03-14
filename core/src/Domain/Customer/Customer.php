<?php declare(strict_types=1);

namespace App\Domain\Customer;

use App\Domain\Common\Address;
use App\Domain\Identity\Entity;
use App\Domain\Identity\Exception\NameEmptyException;

class Customer extends Entity
{
    private string $name;
    private string $surname;
    private Membership $membership;
    private ContactInfo $contact;
    private Address $address;
    private string $vatNumber;

    public function __construct(
        string      $name,
        string      $surname,
        Membership  $membership,
        ContactInfo $contact,
        Address     $address,
        string      $vatNumber,
        string      $createdBy,
    ) {
        parent::__construct($createdBy);
        $this->check($name);

        $this->name = $name;
        $this->surname = $surname;
        $this->membership = $membership;
        $this->contact = $contact;
        $this->address = $address;
        $this->vatNumber = $vatNumber;
    }

    public function modify(
        string $name,
        string $surname,
        ContactInfo $contact,
        Address $address,
        string $vatNumber,
        bool $active,
        string $updatedBy,
    ): void
    {
        $this->check($name);

        $this->name = $name;
        $this->surname = $surname;
        $this->contact = $contact;
        $this->address = $address;
        $this->vatNumber = $vatNumber;

        if ($active) {
            $this->membership->enable();
        } else {
            $this->membership->disable();
        }

        $this->updated($updatedBy);
    }

    private function check(string $name): void
    {
        if (empty($name)) {
            throw new NameEmptyException();
        }
    }

    public function getDescriptor(): CustomerDescriptor
    {
        return new CustomerDescriptor(
            $this->id,
            $this->name,
            $this->surname,
            $this->vatNumber,
            $this->membership->getNumber(),
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getMembership(): Membership
    {
        return $this->membership;
    }

    public function getContact(): ContactInfo
    {
        return $this->contact;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getVatNumber(): string
    {
        return $this->vatNumber;
    }
}
