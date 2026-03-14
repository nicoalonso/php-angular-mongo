<?php declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Presentation\Identity\Result;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Throwable;

/**
 * @codeCoverageIgnore
 */
final class LogoutController
{
    public function __invoke(Request $request): Response
    {
        try {
            $session = $request->getSession();
            $session->clear();
            $session->invalidate();
            $session->save();

        } catch (Throwable $e) {
            throw new ServiceUnavailableHttpException(null, $e->getMessage());
        }

        return new JsonResponse(Result::success());
    }
}
