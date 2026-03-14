<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Editorial;

use App\Application\Editorial\Creator\EditorialCreate;
use App\Application\Editorial\Creator\EditorialCreatePayload;
use App\Domain\Identity\Exception\BadRequestException;
use App\Presentation\V1\Editorial\EditorialReadView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class EditorialCreateController
{
    public function __invoke(Request $request, EditorialCreate $creator): Response
    {
        try {
            $data = $request->request->all();
            $payload = new EditorialCreatePayload($data);
            $editorial = $creator->dispatch($payload);
            $view = new EditorialReadView($editorial);

        } catch (BadRequestException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new JsonResponse($view, Response::HTTP_CREATED);
    }
}
