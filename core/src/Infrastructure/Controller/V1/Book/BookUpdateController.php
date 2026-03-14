<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Book;

use App\Application\Book\Updater\BookUpdate;
use App\Application\Book\Updater\BookUpdatePayload;
use App\Domain\Book\Exception\BookNotFoundException;
use App\Domain\Identity\Exception\BadRequestException;
use App\Domain\Identity\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class BookUpdateController
{
    public function __invoke(string $bookId, Request $request, BookUpdate $updater): Response
    {
        try {
            $data = $request->request->all();
            $payload = new BookUpdatePayload($data);
            $updater->dispatch($bookId, $payload);

        } catch (BookNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (BadRequestException|NotFoundException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
