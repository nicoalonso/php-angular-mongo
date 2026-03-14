<?php declare(strict_types=1);

namespace App\Application\Editorial\Creator;

use App\Domain\Common\Address;
use App\Domain\Common\EnterpriseContact;
use App\Domain\Editorial\Editorial;
use App\Domain\Editorial\EditorialRepository;
use App\Domain\Editorial\Exception\EditorialAlreadyExistsException;
use App\Domain\User\UserRepository;

final readonly class EditorialCreate
{
    public function __construct(
        private EditorialRepository $repoEditorial,
        private UserRepository $repoUser,
    ) {}

    public function dispatch(EditorialCreatePayload $payload): Editorial
    {
        $this->checkAlreadyExists($payload);

        $contact = new EnterpriseContact(
            $payload->getContact()->getEmail(),
            $payload->getContact()->getWebsite(),
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

        $editorial = new Editorial(
            $payload->getName(),
            $payload->getComercialName(),
            $contact,
            $address,
            $user->getName(),
        );
        $this->repoEditorial->save($editorial);

        return $editorial;
    }

    private function checkAlreadyExists(EditorialCreatePayload $payload): void
    {
        $editorial = $this->repoEditorial->obtainByName($payload->getName());
        if (null !== $editorial) {
            throw new EditorialAlreadyExistsException($payload->getName());
        }
    }
}
