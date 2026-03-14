<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Customer;

use App\Application\Customer\Reader\CustomerRead;
use App\Domain\Identity\Exception\NotFoundException;
use App\Presentation\V1\Customer\CustomerReadView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CustomerReadController
{
    public function __invoke(string $customerId, CustomerRead $reader): Response
    {
        try {
            $customer = $reader->dispatch($customerId);
            $result = new CustomerReadView($customer);

        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return new JsonResponse($result);
    }
}
