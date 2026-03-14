<?php declare(strict_types=1);

namespace App\Application\Purchase\Creator;

use App\Application\Purchase\Creator\Payload\PurchaseInvoicePayload;
use App\Application\Purchase\Creator\Payload\PurchaseLinePayload;
use App\Domain\Identity\Payload;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class PurchaseCreatePayload extends Payload
{
    private string $providerId;
    private ?DateTimeImmutable $purchasedAt;
    private PurchaseInvoicePayload $invoice;
    /** @var Collection<PurchaseLinePayload> */
    private Collection $lines;

    public function __construct(array $payload)
    {
        parent::__construct($payload);

        $this->providerId = $this->data->toString('providerId');
        $this->purchasedAt = $this->data->toDateImmutable('purchasedAt', DATE_SHORT);
        $this->invoice = new PurchaseInvoicePayload($this->data->toArray('invoice'));

        $this->lines = new ArrayCollection();
        $lineList = $this->data->toArray('lines');
        foreach ($lineList as $line) {
            $this->lines->add(new PurchaseLinePayload($line));
        }
    }

    public function getProviderId(): string
    {
        return $this->providerId;
    }

    public function getPurchasedAt(): ?DateTimeImmutable
    {
        return $this->purchasedAt;
    }

    public function getInvoice(): PurchaseInvoicePayload
    {
        return $this->invoice;
    }

    /**
     * @return Collection<PurchaseLinePayload>
     */
    public function getLines(): Collection
    {
        return $this->lines;
    }
}
