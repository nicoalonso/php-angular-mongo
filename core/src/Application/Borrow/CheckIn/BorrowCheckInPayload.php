<?php declare(strict_types=1);

namespace App\Application\Borrow\CheckIn;

use App\Application\Borrow\Creator\Payload\BorrowLinePayload;
use App\Domain\Identity\Payload;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class BorrowCheckInPayload extends Payload
{
    /** @var Collection<BorrowLinePayload> */
    private Collection $lines;

    public function __construct(array $payload)
    {
        parent::__construct($payload);

        $this->lines = new ArrayCollection();
        $lineList = $this->data->toArray('lines');
        foreach ($lineList as $line) {
            $this->lines->add(new BorrowLinePayload($line));
        }
    }

    public function getLines(): Collection
    {
        return $this->lines;
    }
}
