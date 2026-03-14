<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Customer;

use App\Application\Customer\Updater\CustomerUpdate;
use App\Application\Customer\Updater\CustomerUpdatePayload;
use App\Domain\Identity\Exception\BadRequestException;
use App\Domain\Identity\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CustomerUpdateController
{
    public function __invoke(string $customerId, Request $request, CustomerUpdate $updater): Response
    {
        try {
            $data = $request->request->all();
            $payload = new CustomerUpdatePayload($data);
            $updater->dispatch($customerId, $payload);

        } catch (BadRequestException $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
