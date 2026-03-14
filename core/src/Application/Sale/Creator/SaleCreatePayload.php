<?php declare(strict_types=1);

namespace App\Application\Sale\Creator;

use App\Application\Sale\Creator\Payload\SaleInvoicePayload;
use App\Application\Sale\Creator\Payload\SaleLinePayload;
use App\Domain\Identity\Payload;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class SaleCreatePayload extends Payload
{
    private string $customerId;
    private SaleInvoicePayload $invoice;
    /** @var Collection<SaleLinePayload> */
    private Collection $lines;

    public function __construct(array $payload)
    {
        parent::__construct($payload);

        $this->customerId = $this->data->toString('customerId');
        $this->invoice = new SaleInvoicePayload($this->data->toArray('invoice'));

        $this->lines = new ArrayCollection();
        $lineList = $this->data->toArray('lines');
        foreach ($lineList as $line) {
            $this->lines->add(new SaleLinePayload($line));
        }
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public function getInvoice(): SaleInvoicePayload
    {
        return $this->invoice;
    }

    /**
     * @return Collection<SaleLinePayload>
     */
    public function getLines(): Collection
    {
        return $this->lines;
    }
}
