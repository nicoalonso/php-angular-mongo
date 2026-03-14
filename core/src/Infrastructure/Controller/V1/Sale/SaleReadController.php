<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Sale;

use App\Application\Sale\Reader\SaleRead;
use App\Domain\Identity\Exception\NotFoundException;
use App\Presentation\V1\Sale\SaleReadView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class SaleReadController
{
    public function __invoke(string $saleId, SaleRead $reader): Response
    {
        try {
            $sale = $reader->dispatch($saleId);
            $result = new SaleReadView($sale);

        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return new JsonResponse($result);
    }
}
