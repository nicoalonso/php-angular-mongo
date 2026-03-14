<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Author;

use App\Application\Author\Updater\AuthorUpdate;
use App\Application\Author\Updater\AuthorUpdatePayload;
use App\Domain\Identity\Exception\BadRequestException;
use App\Domain\Identity\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class AuthorUpdateController
{
    public function __invoke(string $authorId, Request $request, AuthorUpdate $updater): Response
    {
        try {
            $data = $request->request->all();
            $payload = new AuthorUpdatePayload($data);
            $updater->dispatch($authorId, $payload);

        } catch (BadRequestException $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
