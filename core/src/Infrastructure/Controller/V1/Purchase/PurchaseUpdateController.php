<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Purchase;

use App\Application\Purchase\Updater\PurchaseUpdate;
use App\Application\Purchase\Updater\PurchaseUpdatePayload;
use App\Domain\Book\Exception\BookNotFoundException;
use App\Domain\Identity\Exception\BadRequestException;
use App\Domain\Provider\Exception\ProviderNotFoundException;
use App\Domain\Purchase\Exception\PurchaseNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PurchaseUpdateController
{
    public function __invoke(string $purchaseId, Request $request, PurchaseUpdate $updater): Response
    {
        try {
            $data = $request->request->all();
            $payload = new PurchaseUpdatePayload($data);
            $updater->dispatch($purchaseId, $payload);

        } catch (BadRequestException|ProviderNotFoundException|BookNotFoundException $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (PurchaseNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
