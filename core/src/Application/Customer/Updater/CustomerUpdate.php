<?php declare(strict_types=1);

namespace App\Application\Customer\Updater;

use App\Domain\Common\Address;
use App\Domain\Customer\ContactInfo;
use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerRepository;
use App\Domain\Customer\Exception\CustomerNotFoundException;
use App\Domain\User\UserRepository;

final readonly class CustomerUpdate
{
    public function __construct(
        private CustomerRepository $repoCustomer,
        private UserRepository     $repoUser,
    ) {}

    public function dispatch(string $customerId, CustomerUpdatePayload $payload): Customer
    {
        $customer = $this->repoCustomer->obtainById($customerId);
        if (null === $customer) {
            throw new CustomerNotFoundException();
        }

        $user = $this->repoUser->obtainUser();
        $contact = new ContactInfo(
            $payload->getContact()->getEmail(),
            $payload->getContact()->getPhone1(),
            $payload->getContact()->getPhone2(),
        );
        $address = new Address(
            $payload->getAddress()->getStreet(),
            $payload->getAddress()->getPostalCode(),
            $payload->getAddress()->getCity(),
            $payload->getAddress()->getProvince(),
            $payload->getAddress()->getCountry(),
        );

        $customer->modify(
            $payload->getName(),
            $payload->getSurname(),
            $contact,
            $address,
            $payload->getVatNumber(),
            $payload->isActive(),
            $user->getName(),
        );
        $this->repoCustomer->save($customer);

        return $customer;
    }
}
