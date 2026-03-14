<?php declare(strict_types=1);

namespace App\Infrastructure\EventListener;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * @codeCoverageIgnore
 */
final class RequestJsonListener
{
    private const string HEADER_APPLICATION_JSON = 'application/json';
    private const string CONTENT_TYPE_HEADER = 'content-type';

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            // don't do anything if it's not the master request
            return;
        }

        $request = $event->getRequest();
        if (
            !empty($request->getContent()) &&
            (self::HEADER_APPLICATION_JSON === $request->headers->get(self::CONTENT_TYPE_HEADER))
        ) {
            $params = json_decode($request->getContent(), true);
            if (null !== $params) {
                $request->request->add($params);
            } else {
                throw new BadRequestException('Invalid JSON formatting');
            }
        }
    }
}
