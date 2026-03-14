<?php declare(strict_types=1);

namespace App\Application\Customer\Reader;

use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerRepository;
use App\Domain\Customer\Exception\CustomerNotFoundException;

final readonly class CustomerRead
{
    public function __construct(private CustomerRepository $repoCustomer) {}

    public function dispatch(string $customerId): Customer
    {
        $customer = $this->repoCustomer->obtainById($customerId);
        if (null === $customer) {
            throw new CustomerNotFoundException();
        }

        return $customer;
    }
}
