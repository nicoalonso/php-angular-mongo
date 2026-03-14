<?php declare(strict_types=1);

namespace App\Application\Customer\Eraser;

use App\Domain\Borrow\BorrowRepository;
use App\Domain\Customer\CustomerRepository;
use App\Domain\Customer\Exception\CustomerNotFoundException;
use App\Domain\Sale\SaleRepository;

final readonly class CustomerDelete
{
    public function __construct(
        private CustomerRepository $repoCustomer,
        private SaleRepository $repoSale,
        private BorrowRepository $repoBorrow,
    ) {}

    public function dispatch(string $customerId): void
    {
        $customer = $this->repoCustomer->obtainById($customerId);
        if (null === $customer) {
            throw new CustomerNotFoundException();
        }

        $this->checkAssociated($customerId);

        $this->repoCustomer->remove($customer);
    }

    private function checkAssociated(string $customerId): void
    {
        $borrows = $this->repoBorrow->obtainByCustomer($customerId, 1);
        if (!$borrows->isEmpty()) {
            throw new CustomerAssociatedException('The customer is associated with one or more borrows.');
        }

        $sales = $this->repoSale->obtainByCustomer($customerId, 1);
        if (!$sales->isEmpty()) {
            throw new CustomerAssociatedException('The customer is associated with one or more sales.');
        }
    }
}
