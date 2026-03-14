<?php declare(strict_types=1);

namespace App\Application\Provider\Eraser;

use App\Domain\Provider\Exception\ProviderNotFoundException;
use App\Domain\Provider\ProviderRepository;
use App\Domain\Purchase\PurchaseRepository;

final readonly class ProviderDelete
{
    public function __construct(
        private ProviderRepository $repoProvider,
        private PurchaseRepository $repoPurchase,
    ) {}

    public function dispatch(string $providerId): void
    {
        $provider = $this->repoProvider->obtainById($providerId);
        if (null === $provider) {
            throw new ProviderNotFoundException();
        }

        $purchases = $this->repoPurchase->obtainByProvider($providerId, 1);
        if (!$purchases->isEmpty()) {
            throw new ProviderAssociatedException();
        }

        $this->repoProvider->remove($provider);
    }
}
