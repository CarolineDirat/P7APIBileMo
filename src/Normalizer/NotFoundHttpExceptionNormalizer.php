<?php

namespace App\Normalizer;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * NotFoundHttpExceptionNormalizer
 * Normalize a error 404.
 */
class NotFoundHttpExceptionNormalizer extends AppAbstractNormalizer
{
    public function normalize(Throwable $exception): array
    {
        $result['code'] = Response::HTTP_NOT_FOUND;

        $message = $exception->getMessage();
        if ('App\\Entity\\Phone object not found by the @ParamConverter annotation.' === $message) {
            $message = 'Phone not found';
        }
        if ('App\\Entity\\User object not found by the @ParamConverter annotation.' === $message) {
            $message = 'User not found';
        }
        if ('App\\Entity\\Client object not found by the @ParamConverter annotation.' === $message) {
            $message = 'Client not found';
        }

        $result['body'] = [
            'code' => Response::HTTP_NOT_FOUND,
            'message' => $message,
        ];

        return $result;
    }
}
