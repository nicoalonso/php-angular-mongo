<?php declare(strict_types=1);

namespace App\Application\Purchase\Creator;

use App\Domain\Book\BookRepository;
use App\Domain\Bus\DomainBus;
use App\Domain\Provider\ProviderRepository;
use App\Domain\Purchase\Exception\PurchaseAlreadyExistsException;
use App\Domain\Purchase\Purchase;
use App\Domain\Purchase\PurchaseLineRepository;
use App\Domain\Purchase\PurchaseRepository;
use App\Domain\User\UserRepository;

final class PurchaseCreate
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

    public function dispatch(PurchaseCreatePayload $payload): Purchase
    {
        $this->check($payload);
        $this->chekAlreadyExists($payload);

        $provider = $this->findProvider($payload->getProviderId());
        $invoice = $this->makeInvoice($payload->getInvoice());
        $user = $this->repoUser->obtainUser();

        $purchase = new Purchase(
            $provider,
            $payload->getPurchasedAt(),
            $invoice,
            $user->getName(),
        );
        $this->repoPurchase->save($purchase);

        $this->manageLines($purchase, $payload->getLines());

        $books = $this->getBookList();
        $event = new PurchaseCreatedEvent($purchase, $books);
        $this->bus->dispatch($event);

        return $purchase;
    }

    private function chekAlreadyExists(PurchaseCreatePayload $payload): void
    {
        $purchase = $this->repoPurchase->obtainByProviderAndNumber(
            $payload->getProviderId(),
            $payload->getInvoice()->getNumber(),
        );
        if (null !== $purchase) {
            throw new PurchaseAlreadyExistsException();
        }
    }
}
