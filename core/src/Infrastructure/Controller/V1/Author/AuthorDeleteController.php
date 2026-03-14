<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Author;

use App\Application\Author\Eraser\AuthorDelete;
use App\Domain\Identity\Exception\BadRequestException;
use App\Domain\Identity\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class AuthorDeleteController
{
    public function __invoke(string $authorId, AuthorDelete $eraser): Response
    {
        try {
            $eraser->dispatch($authorId);

        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (BadRequestException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
