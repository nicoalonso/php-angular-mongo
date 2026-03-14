<?php declare(strict_types=1);

namespace App\Infrastructure\EventListener;

use App\Presentation\Identity\Result;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * @codeCoverageIgnore
 */
final class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$event->isMainRequest()) {
            // don't do anything if it's not the master request
            return;
        }

        $exception = $event->getThrowable();
        $code = $exception->getCode();
        if (0 === $code && $exception instanceof HttpExceptionInterface) {
            $code = $exception->getStatusCode();
        }

        $result = Result::exception($code, $exception);
        $response = new JsonResponse($result);
        $event->setResponse($response);
    }
}
