<?php declare(strict_types=1);

namespace App\Application\Borrow\Creator;

use App\Application\Borrow\Creator\Payload\BorrowLinePayload;
use App\Domain\Identity\Payload;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class BorrowCreatePayload extends Payload
{
    private string $customerId;
    /** @var Collection<BorrowLinePayload> */
    private Collection $lines;

    public function __construct(array $payload)
    {
        parent::__construct($payload);

        $this->customerId = $this->data->toString('customerId');

        $this->lines = new ArrayCollection();
        $lineList = $this->data->toArray('lines');
        foreach ($lineList as $line) {
            $this->lines->add(new BorrowLinePayload($line));
        }
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public function getLines(): Collection
    {
        return $this->lines;
    }
}
