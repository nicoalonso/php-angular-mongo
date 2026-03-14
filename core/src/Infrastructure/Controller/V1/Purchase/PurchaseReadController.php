<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Purchase;

use App\Application\Purchase\Reader\PurchaseRead;
use App\Domain\Identity\Exception\NotFoundException;
use App\Presentation\V1\Purchase\PurchaseReadView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PurchaseReadController
{
    public function __invoke(string $purchaseId, PurchaseRead $reader): Response
    {
        try {
            $purchase = $reader->dispatch($purchaseId);
            $result = new PurchaseReadView($purchase);

        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return new JsonResponse($result);
    }
}
