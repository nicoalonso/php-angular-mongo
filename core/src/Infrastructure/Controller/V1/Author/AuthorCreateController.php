<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Author;

use App\Application\Author\Creator\AuthorCreate;
use App\Application\Author\Creator\AuthorCreatePayload;
use App\Domain\Identity\Exception\BadRequestException;
use App\Presentation\V1\Author\AuthorReadView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class AuthorCreateController
{
    public function __invoke(Request $request, AuthorCreate $creator): Response
    {
        try {
            $data = $request->request->all();
            $payload = new AuthorCreatePayload($data);
            $author = $creator->dispatch($payload);
            $view = new AuthorReadView($author);

        } catch (BadRequestException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new JsonResponse($view, Response::HTTP_CREATED);
    }
}
