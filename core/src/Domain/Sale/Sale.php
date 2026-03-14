<?php declare(strict_types=1);

namespace App\Domain\Sale;

use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerDescriptor;
use App\Domain\Identity\Entity;
use App\Domain\Sale\Exception\InvalidSaleInvoiceNumberException;

class Sale extends Entity
{
    private CustomerDescriptor $customer;
    private string $number;
    private SaleInvoice $invoice;

    public function __construct(
        Customer $customer,
        string $number,
        SaleInvoice $invoice,
        string $createdBy,
    )
    {
        parent::__construct($createdBy);
        $this->check($number);

        $this->customer = $customer->getDescriptor();
        $this->number = $number;
        $this->invoice = $invoice;
    }

    private function check(string $number): void
    {
        if (empty($number)) {
            throw new InvalidSaleInvoiceNumberException();
        }
    }

    public function getCustomer(): CustomerDescriptor
    {
        return $this->customer;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getInvoice(): SaleInvoice
    {
        return $this->invoice;
    }
}
