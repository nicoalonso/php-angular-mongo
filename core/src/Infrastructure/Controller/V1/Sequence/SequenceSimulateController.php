<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Sequence;

use App\Application\Sequence\Simulator\SequenceSimulate;
use App\Domain\Identity\Exception\BadRequestException;
use App\Presentation\Identity\Result;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class SequenceSimulateController
{
    public function __invoke(string $type, SequenceSimulate $simulator): Response
    {
        try {
            $number = $simulator->dispatch($type);
            $result = Result::success(['number' => $number->format()]);

        } catch (BadRequestException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new JsonResponse($result);
    }
}
