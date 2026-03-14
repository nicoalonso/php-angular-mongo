<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Provider;

use App\Application\Provider\Updater\ProviderUpdate;
use App\Application\Provider\Updater\ProviderUpdatePayload;
use App\Domain\Identity\Exception\BadRequestException;
use App\Domain\Identity\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ProviderUpdateController
{
    public function __invoke(string $providerId, Request $request, ProviderUpdate $updater): Response
    {
        try {
            $data = $request->request->all();
            $payload = new ProviderUpdatePayload($data);
            $updater->dispatch($providerId, $payload);

            // @codeCoverageIgnoreStart
        } catch (BadRequestException $e) {
            throw new BadRequestHttpException($e->getMessage());
            // @codeCoverageIgnoreEnd
        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
