<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Book;

use App\Application\Book\Available\BookAvailable;
use App\Domain\Identity\Exception\BadRequestException;
use App\Domain\Identity\Exception\NotFoundException;
use App\Presentation\Identity\Result;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class BookAvailableController
{
    public function __invoke(string $bookId, Request $request, BookAvailable $available): Response
    {
        try {
            $isSale = $request->query->has('sale');
            $isAvailable = $available->dispatch($bookId, $isSale);
            $result = [
                'available' => $isAvailable,
            ];

            // @codeCoverageIgnoreStart
        } catch (BadRequestException $e) {
            throw new BadRequestHttpException($e->getMessage());
            // @codeCoverageIgnoreEnd
        } catch (NotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return new JsonResponse(Result::success($result));
    }
}
