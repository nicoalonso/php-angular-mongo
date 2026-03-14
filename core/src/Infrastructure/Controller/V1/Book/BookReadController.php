<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Book;

use App\Application\Book\Reader\BookRead;
use App\Domain\Identity\Exception\NotFoundException;
use App\Presentation\V1\Book\BookReadView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class BookReadController
{
    public function __invoke(string $bookId, BookRead $reader): Response
    {
        try {
            $book = $reader->dispatch($bookId);
            $result = new BookReadView($book);

        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return new JsonResponse($result);
    }
}
