<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Customer;

use App\Application\Customer\Creator\CustomerCreate;
use App\Application\Customer\Creator\CustomerCreatePayload;
use App\Domain\Identity\Exception\BadRequestException;
use App\Presentation\V1\Customer\CustomerReadView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class CustomerCreateController
{
    public function __invoke(Request $request, CustomerCreate $creator): Response
    {
        try {
            $data = $request->request->all();
            $payload = new CustomerCreatePayload($data);
            $customer = $creator->dispatch($payload);
            $view = new CustomerReadView($customer);

        } catch (BadRequestException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new JsonResponse($view, Response::HTTP_CREATED);
    }
}
