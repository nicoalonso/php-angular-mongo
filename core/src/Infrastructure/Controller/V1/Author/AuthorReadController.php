<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Author;

use App\Application\Author\Reader\AuthorRead;
use App\Domain\Identity\Exception\NotFoundException;
use App\Presentation\V1\Author\AuthorReadView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class AuthorReadController
{
    public function __invoke(string $authorId, AuthorRead $reader): Response
    {
        try {
            $author = $reader->dispatch($authorId);
            $result = new AuthorReadView($author);

        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return new JsonResponse($result);
    }
}
