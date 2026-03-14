<?php declare(strict_types=1);

namespace App\Domain\Purchase;

use App\Domain\Identity\Entity;
use App\Domain\Provider\Provider;
use App\Domain\Provider\ProviderDescriptor;
use App\Domain\Purchase\Exception\InvalidPurchaseDateException;
use DateTimeImmutable;

class Purchase extends Entity
{
    private ProviderDescriptor $provider;
    private DateTimeImmutable $purchasedAt;
    private PurchaseInvoice $invoice;

    public function __construct(
        Provider $provider,
        DateTimeImmutable $purchasedAt,
        PurchaseInvoice $invoice,
        string $createdBy,
    ) {
        parent::__construct($createdBy);
        $this->check($purchasedAt);

        $this->provider = $provider->getDescriptor();
        $this->purchasedAt = $purchasedAt;
        $this->invoice = $invoice;
    }

    public function modify(
        Provider $provider,
        DateTimeImmutable $purchasedAt,
        PurchaseInvoice $invoice,
        string $updatedBy,
    ): void
    {
        $this->check($purchasedAt);

        $this->provider = $provider->getDescriptor();
        $this->purchasedAt = $purchasedAt;
        $this->invoice = $invoice;

        $this->updated($updatedBy);
    }

    private function check(DateTimeImmutable $purchasedAt): void
    {
        $now = new DateTimeImmutable('today midnight +1 day');
        if ($purchasedAt > $now) {
            throw new InvalidPurchaseDateException();
        }
    }

    public function getProvider(): ProviderDescriptor
    {
        return $this->provider;
    }

    public function getPurchasedAt(): DateTimeImmutable
    {
        return $this->purchasedAt;
    }

    public function getInvoice(): PurchaseInvoice
    {
        return $this->invoice;
    }
}
