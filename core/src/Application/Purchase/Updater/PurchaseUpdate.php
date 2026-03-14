<?php declare(strict_types=1);

namespace App\Application\Purchase\Updater;

use App\Application\Purchase\Creator\PurchaseMakeable;
use App\Domain\Book\BookRepository;
use App\Domain\Bus\DomainBus;
use App\Domain\Provider\ProviderRepository;
use App\Domain\Purchase\Exception\PurchaseNotFoundException;
use App\Domain\Purchase\Purchase;
use App\Domain\Purchase\PurchaseLineRepository;
use App\Domain\Purchase\PurchaseRepository;
use App\Domain\User\UserRepository;

final class PurchaseUpdate
{
    use PurchaseMakeable;

    public function __construct(
        private readonly PurchaseRepository $repoPurchase,
        private readonly PurchaseLineRepository $repoPurchaseLine,
        private readonly ProviderRepository $repoProvider,
        private readonly BookRepository $repoBook,
        private readonly UserRepository $repoUser,
        private readonly DomainBus $bus,
    ) {}

    public function dispatch(string $purchaseId, PurchaseUpdatePayload $payload): Purchase
    {
        $purchase = $this->repoPurchase->obtainById($purchaseId);
        if (null === $purchase) {
            throw new PurchaseNotFoundException();
        }

        $this->check($payload);

        $provider = $this->findProvider($payload->getProviderId());
        $invoice = $this->makeInvoice($payload->getInvoice());
        $user = $this->repoUser->obtainUser();

        $purchase->modify($provider, $payload->getPurchasedAt(), $invoice, $user->getName());

        $currentLines = $this->repoPurchaseLine->obtainByPurchase($purchase->getId());
        $this->manageLines($purchase, $payload->getLines(), $currentLines);

        $this->repoPurchase->save($purchase);

        $books = $this->getBookList();
        $event = new PurchaseUpdatedEvent($purchase, $books);
        $this->bus->dispatch($event);

        return $purchase;
    }
}
