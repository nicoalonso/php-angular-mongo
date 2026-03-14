<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Provider;

use App\Application\Provider\Reader\ProviderRead;
use App\Domain\Identity\Exception\NotFoundException;
use App\Presentation\V1\Provider\ProviderReadView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ProviderReadController
{
    public function __invoke(string $providerId, ProviderRead $reader): Response
    {
        try {
            $provider = $reader->dispatch($providerId);
            $result = new ProviderReadView($provider);

        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return new JsonResponse($result);
    }
}
