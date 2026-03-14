<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Borrow;

use App\Application\Borrow\CheckIn\BorrowCheckIn;
use App\Application\Borrow\CheckIn\BorrowCheckInPayload;
use App\Domain\Identity\Exception\BadRequestException;
use App\Domain\Identity\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class BorrowCheckInController
{
    public function __invoke(string $borrowId, Request $request, BorrowCheckIn $checker): Response
    {
        try {
            $data = $request->request->all();
            $payload = new BorrowCheckInPayload($data);
            $checker->dispatch($borrowId, $payload);

            // @codeCoverageIgnoreStart
        } catch (BadRequestException $e) {
            throw new BadRequestHttpException($e->getMessage());
            // @codeCoverageIgnoreEnd
        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
