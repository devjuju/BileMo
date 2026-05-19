<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onExceptionEvent(ExceptionEvent $event): void
    {
        $request = $event->getRequest();

        // 👉 API only
        if (!str_starts_with($request->getPathInfo(), '/api')) {
            return;
        }

        $exception = $event->getThrowable();

        $status = 500;
        $message = 'Internal Server Error';
        $errorCode = 'INTERNAL_ERROR';
        $errors = null;

        if ($exception instanceof HttpExceptionInterface) {
            $status = $exception->getStatusCode();

            $message = match ($status) {
                403 => 'Access denied',
                404 => 'Resource not found',
                401 => 'Unauthorized',
                default => $exception->getMessage() ?: 'HTTP error',
            };

            $errorCode = match ($status) {
                403 => 'FORBIDDEN',
                404 => 'NOT_FOUND',
                401 => 'UNAUTHORIZED',
                default => 'HTTP_ERROR',
            };
        }

        $event->setResponse(new JsonResponse([
            'status' => $status,
            'message' => $message,
            'error_code' => $errorCode,
            'errors' => $errors,
        ], $status));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => 'onExceptionEvent',
        ];
    }
}
