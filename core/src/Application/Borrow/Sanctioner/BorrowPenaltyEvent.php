<?php declare(strict_types=1);

namespace App\Application\Borrow\Sanctioner;

use App\Domain\Bus\DomainEvent;
use App\Domain\Bus\DomainRoute;

final class BorrowPenaltyEvent extends DomainEvent
{
    public const string ACTION = 'borrow.penalty';
    private const string TYPE = 'borrow';

    public function __construct()
    {
        parent::__construct(self::ACTION, self::TYPE, DomainRoute::LIBRARY);
    }
}
