<?php declare(strict_types=1);

namespace App\Application\Provider\Creator;

use App\Domain\Common\Address;
use App\Domain\Common\EnterpriseContact;
use App\Domain\Provider\Exception\ProviderAlreadyExistsException;
use App\Domain\Provider\Provider;
use App\Domain\Provider\ProviderRepository;
use App\Domain\User\UserRepository;

final readonly class ProviderCreate
{
    public function __construct(
        private ProviderRepository $repoProvider,
        private UserRepository $repoUser,
    ) {}

    public function dispatch(ProviderCreatePayload $payload): Provider
    {
        $this->checkAlreadyExists($payload);

        $user = $this->repoUser->obtainUser();
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

        $provider = new Provider(
            $payload->getName(),
            $payload->getComercialName(),
            $contact,
            $address,
            $payload->getVatNumber(),
            $user->getName(),
        );
        $this->repoProvider->save($provider);

        return $provider;
    }

    private function checkAlreadyExists(ProviderCreatePayload $payload): void
    {
        $provider = $this->repoProvider->obtainByName($payload->getName());
        if (null !== $provider) {
            throw new ProviderAlreadyExistsException();
        }
    }
}
