<?php declare(strict_types=1);

namespace App\Application\Editorial\Updater;

use App\Domain\Common\Address;
use App\Domain\Common\EnterpriseContact;
use App\Domain\Editorial\Editorial;
use App\Domain\Editorial\EditorialRepository;
use App\Domain\Editorial\Exception\EditorialNotFoundException;
use App\Domain\User\UserRepository;

final readonly class EditorialUpdate
{
    public function __construct(
        private EditorialRepository $repoEditorial,
        private UserRepository $repoUser,
    ) {}

    public function dispatch(string $editorialId, EditorialUpdatePayload $payload): Editorial
    {
        $editorial = $this->repoEditorial->obtainById($editorialId);
        if (null === $editorial) {
            throw new EditorialNotFoundException();
        }

        $user = $this->repoUser->obtainUser();
        $address = new Address(
            $payload->getAddress()->getStreet(),
            $payload->getAddress()->getPostalCode(),
            $payload->getAddress()->getCity(),
            $payload->getAddress()->getProvince(),
            $payload->getAddress()->getCountry(),
        );
        $contact = new EnterpriseContact(
            $payload->getContact()->getEmail(),
            $payload->getContact()->getWebsite(),
            $payload->getContact()->getPhone1(),
            $payload->getContact()->getPhone2(),
        );

        $editorial->modify(
            $payload->getName(),
            $payload->getComercialName(),
            $contact,
            $address,
            $user->getName(),
        );
        $this->repoEditorial->save($editorial);

        return $editorial;
    }
}
