<?php declare(strict_types=1);

namespace App\Presentation\V1\Customer;

use App\Domain\Customer\Customer;
use App\Presentation\Identity\Result;

final class CustomerReadView extends Result
{
    public function __construct(Customer $customer)
    {
        parent::__construct($customer);
    }

    /**
     * @param Customer $data
     */
    public static function serialize(mixed $data): array
    {
        return [
            'id' => $data->getId(),
            'name' => $data->getName(),
            'surname' => $data->getSurname(),
            'membership' => [
                'number' => $data->getMembership()->getNumber(),
                'active' => $data->getMembership()->isActive(),
                'endedAt' => $data->getMembership()->getEndedAt()?->format(DATE_ATOM),
            ],
            'contact' => [
                'email' => $data->getContact()->getEmail(),
                'phone1' => $data->getContact()->getPhone1(),
                'phone2' => $data->getContact()->getPhone2(),
            ],
            'address' => [
                'street' => $data->getAddress()->getStreet(),
                'postalCode' => $data->getAddress()->getPostalCode(),
                'city' => $data->getAddress()->getCity(),
                'province' => $data->getAddress()->getProvince(),
                'country' => $data->getAddress()->getCountry(),
            ],
            'vatNumber' => $data->getVatNumber(),
            'createdBy' => $data->getCreatedBy(),
            'createdAt' => $data->getCreatedAt()->format(DATE_ATOM),
            'updatedBy' => $data->getUpdatedBy(),
            'updatedAt' => $data->getUpdatedAt()?->format(DATE_ATOM),
        ];
    }
}
