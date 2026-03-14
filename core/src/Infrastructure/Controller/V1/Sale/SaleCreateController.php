<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Sale;

use App\Application\Sale\Creator\SaleCreate;
use App\Application\Sale\Creator\SaleCreatePayload;
use App\Domain\Identity\Exception\BadRequestException;
use App\Domain\Identity\Exception\NotFoundException;
use App\Presentation\V1\Sale\SaleListView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class SaleCreateController
{
    public function __invoke(Request $request, SaleCreate $creator): Response
    {
        try {
            $data = $request->request->all();
            $payload = new SaleCreatePayload($data);
            $sale = $creator->dispatch($payload);
            $view = new SaleListView($sale);

        } catch (BadRequestException|NotFoundException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new JsonResponse($view, Response::HTTP_CREATED);
    }
}
