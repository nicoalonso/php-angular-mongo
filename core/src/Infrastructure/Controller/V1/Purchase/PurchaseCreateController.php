<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Purchase;

use App\Application\Purchase\Creator\PurchaseCreate;
use App\Application\Purchase\Creator\PurchaseCreatePayload;
use App\Domain\Identity\Exception\BadRequestException;
use App\Domain\Identity\Exception\NotFoundException;
use App\Presentation\V1\Purchase\PurchaseListView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class PurchaseCreateController
{
    public function __invoke(Request $request, PurchaseCreate $creator): Response
    {
        try {
            $data = $request->request->all();
            $payload = new PurchaseCreatePayload($data);
            $purchase = $creator->dispatch($payload);
            $view = new PurchaseListView($purchase);

        } catch (BadRequestException|NotFoundException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new JsonResponse($view, Response::HTTP_CREATED);
    }
}
