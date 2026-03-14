<?php declare(strict_types=1);

namespace App\Presentation\V1\Editorial;

use App\Domain\Editorial\Editorial;
use App\Presentation\Identity\Result;

final class EditorialReadView extends Result
{
    public function __construct(Editorial $editorial)
    {
        parent::__construct($editorial);
    }

    /**
     * @param Editorial $data
     */
    public static function serialize(mixed $data): array
    {
        return [
            'id' => $data->getId(),
            'name' => $data->getName(),
            'comercialName' => $data->getComercialName(),
            'contact' => [
                'email' => $data->getContact()->getEmail(),
                'website' => $data->getContact()->getWebsite(),
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
            'createdBy' => $data->getCreatedBy(),
            'createdAt' => $data->getCreatedAt()->format(DATE_ATOM),
            'updatedBy' => $data->getUpdatedBy(),
            'updatedAt' => $data->getUpdatedAt()?->format(DATE_ATOM),
        ];
    }
}
