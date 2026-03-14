<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Provider;

use App\Application\Provider\Creator\ProviderCreate;
use App\Application\Provider\Creator\ProviderCreatePayload;
use App\Domain\Identity\Exception\BadRequestException;
use App\Presentation\V1\Provider\ProviderReadView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class ProviderCreateController
{
    public function __invoke(Request $request, ProviderCreate $creator): Response
    {
        try {
            $data = $request->request->all();
            $payload = new ProviderCreatePayload($data);
            $provider = $creator->dispatch($payload);
            $view = new ProviderReadView($provider);

        } catch (BadRequestException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new JsonResponse($view, Response::HTTP_CREATED);
    }
}
