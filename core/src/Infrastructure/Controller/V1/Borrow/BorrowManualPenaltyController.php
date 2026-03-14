<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Borrow;

use App\Application\Borrow\Sanctioner\BorrowPenaltyEvent;
use App\Domain\Bus\DomainBus;
use Symfony\Component\HttpFoundation\Response;

/**
 * The best approach to implementing this use case is to create a cron job.
 * However, for the sake of simplicity, we will create an endpoint that can be called manually to trigger the penalty process.
 */
final readonly class BorrowManualPenaltyController
{
    public function __construct(private DomainBus $bus) {}

    public function __invoke(): Response
    {
        $event = new BorrowPenaltyEvent();
        $this->bus->dispatch($event);

        return new Response(status: Response::HTTP_ACCEPTED);
    }
}
