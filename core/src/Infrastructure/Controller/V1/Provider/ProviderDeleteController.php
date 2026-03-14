<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Provider;

use App\Application\Provider\Eraser\ProviderDelete;
use App\Domain\Identity\Exception\BadRequestException;
use App\Domain\Identity\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ProviderDeleteController
{
    public function __invoke(string $providerId, ProviderDelete $eraser): Response
    {
        try {
            $eraser->dispatch($providerId);

        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (BadRequestException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
