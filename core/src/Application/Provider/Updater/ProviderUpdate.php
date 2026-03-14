<?php declare(strict_types=1);

namespace App\Application\Provider\Updater;

use App\Domain\Common\Address;
use App\Domain\Common\EnterpriseContact;
use App\Domain\Provider\Exception\ProviderNotFoundException;
use App\Domain\Provider\Provider;
use App\Domain\Provider\ProviderRepository;
use App\Domain\User\UserRepository;

final readonly class ProviderUpdate
{
    public function __construct(
        private ProviderRepository $repoProvider,
        private UserRepository $repoUser,
    ) {}

    public function dispatch(string $providerId, ProviderUpdatePayload $payload): Provider
    {
        $provider = $this->repoProvider->obtainById($providerId);
        if (null === $provider) {
            throw new ProviderNotFoundException();
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

        $provider->modify(
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
}
