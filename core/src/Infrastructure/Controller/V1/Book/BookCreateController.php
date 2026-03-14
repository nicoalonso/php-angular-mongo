<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Book;

use App\Application\Book\Creator\BookCreate;
use App\Application\Book\Creator\BookCreatePayload;
use App\Domain\Identity\Exception\BadRequestException;
use App\Domain\Identity\Exception\NotFoundException;
use App\Presentation\V1\Book\BookReadView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class BookCreateController
{
    public function __invoke(Request $request, BookCreate $creator): Response
    {
        try {
            $data = $request->request->all();
            $payload = new BookCreatePayload($data);
            $book = $creator->dispatch($payload);
            $view = new BookReadView($book);

        } catch (BadRequestException|NotFoundException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new JsonResponse($view, Response::HTTP_CREATED);
    }
}
