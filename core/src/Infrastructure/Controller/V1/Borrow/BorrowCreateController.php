<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Borrow;

use App\Application\Borrow\Creator\BorrowCreate;
use App\Application\Borrow\Creator\BorrowCreatePayload;
use App\Domain\Identity\Exception\BadRequestException;
use App\Domain\Identity\Exception\NotFoundException;
use App\Presentation\V1\Borrow\BorrowListView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class BorrowCreateController
{
    public function __invoke(Request $request, BorrowCreate $creator): Response
    {
        try {
            $data = $request->request->all();
            $payload = new BorrowCreatePayload($data);
            $borrow = $creator->dispatch($payload);
            $view = new BorrowListView($borrow);

        } catch (BadRequestException|NotFoundException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new JsonResponse($view, Response::HTTP_CREATED);
    }
}
