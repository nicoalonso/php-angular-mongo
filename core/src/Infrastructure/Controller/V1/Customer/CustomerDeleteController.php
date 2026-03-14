<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Customer;

use App\Application\Customer\Eraser\CustomerDelete;
use App\Domain\Identity\Exception\BadRequestException;
use App\Domain\Identity\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CustomerDeleteController
{
    public function __invoke(string $customerId, CustomerDelete $eraser): Response
    {
        try {
            $eraser->dispatch($customerId);

        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (BadRequestException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
