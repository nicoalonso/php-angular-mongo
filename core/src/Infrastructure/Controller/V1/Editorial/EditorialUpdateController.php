<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Editorial;

use App\Application\Editorial\Updater\EditorialUpdate;
use App\Application\Editorial\Updater\EditorialUpdatePayload;
use App\Domain\Identity\Exception\BadRequestException;
use App\Domain\Identity\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class EditorialUpdateController
{
    public function __invoke(string $editorialId, Request $request, EditorialUpdate $updater): Response
    {
        try {
            $data = $request->request->all();
            $payload = new EditorialUpdatePayload($data);
            $updater->dispatch($editorialId, $payload);

        } catch (BadRequestException $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
