<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Purchase;

use App\Application\Purchase\Eraser\PurchaseDelete;
use App\Domain\Identity\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PurchaseDeleteController
{
    public function __invoke(string $purchaseId, PurchaseDelete $eraser): Response
    {
        try {
            $eraser->dispatch($purchaseId);

        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
