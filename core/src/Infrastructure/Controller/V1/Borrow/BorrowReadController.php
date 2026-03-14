<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Borrow;

use App\Application\Borrow\Reader\BorrowRead;
use App\Domain\Identity\Exception\NotFoundException;
use App\Presentation\V1\Borrow\BorrowReadView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class BorrowReadController
{
    public function __invoke(string $borrowId, BorrowRead $reader): Response
    {
        try {
            $borrow = $reader->dispatch($borrowId);
            $result = new BorrowReadView($borrow);

        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return new JsonResponse($result);
    }
}
