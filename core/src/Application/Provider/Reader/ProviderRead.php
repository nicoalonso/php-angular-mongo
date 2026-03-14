<?php declare(strict_types=1);

namespace App\Application\Provider\Reader;

use App\Domain\Provider\Exception\ProviderNotFoundException;
use App\Domain\Provider\Provider;
use App\Domain\Provider\ProviderRepository;

final readonly class ProviderRead
{
    public function __construct(private ProviderRepository $repoProvider) {}

    public function dispatch(string $providerId): Provider
    {
        $provider = $this->repoProvider->obtainById($providerId);
        if (null === $provider) {
            throw new ProviderNotFoundException();
        }

        return $provider;
    }
}
