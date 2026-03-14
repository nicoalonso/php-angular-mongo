<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Editorial;

use App\Application\Editorial\Reader\EditorialRead;
use App\Domain\Identity\Exception\NotFoundException;
use App\Presentation\V1\Editorial\EditorialReadView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class EditorialReadController
{
    public function __invoke(string $editorialId, EditorialRead $reader): Response
    {
        try {
            $editorial = $reader->dispatch($editorialId);
            $result = new EditorialReadView($editorial);

        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return new JsonResponse($result);
    }
}
