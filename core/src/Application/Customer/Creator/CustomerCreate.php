<?php declare(strict_types=1);

namespace App\Application\Customer\Creator;

use App\Domain\Common\Address;
use App\Domain\Customer\ContactInfo;
use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerRepository;
use App\Domain\Customer\Exception\CustomerAlreadyExistException;
use App\Domain\Customer\Membership;
use App\Domain\Sequence\SequenceNumberRepository;
use App\Domain\Sequence\SequenceType;
use App\Domain\User\UserRepository;

final readonly class CustomerCreate
{
    public function __construct(
        private CustomerRepository       $repoCustomer,
        private SequenceNumberRepository $repoSequence,
        private UserRepository           $repoUser,
    ) {}

    public function dispatch(CustomerCreatePayload $payload): Customer
    {
        $this->checkAlreadyExists($payload);

        $number = $this->generateMembershipNextNumber();
        $membership = new Membership($number);

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
        $user = $this->repoUser->obtainUser();

        $customer = new Customer(
            $payload->getName(),
            $payload->getSurname(),
            $membership,
            $contact,
            $address,
            $payload->getVatNumber(),
            $user->getName(),
        );
        $this->repoCustomer->save($customer);

        return $customer;
    }

    private function checkAlreadyExists(CustomerCreatePayload $payload): void
    {
        $customer = $this->repoCustomer->obtainByName($payload->getName(), $payload->getSurname());
        if (null !== $customer) {
            throw new CustomerAlreadyExistException();
        }
    }

    private function generateMembershipNextNumber(): string
    {
        do {
            $sequence = $this->repoSequence->nextNumber(SequenceType::MEMBERSHIP);
            $number = $sequence->format();

            $customer = $this->repoCustomer->obtainByNumber($number);
        } while (null !== $customer);

        return $number;
    }
}
