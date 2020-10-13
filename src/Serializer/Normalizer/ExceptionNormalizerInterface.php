<?php

namespace App\Serializer\Normalizer;

use Throwable;

interface ExceptionNormalizerInterface
{
    /**
     * normalize.
     *
     * @param Throwable $exception
     *
     * @return array<string, mixed>
     */
    public function normalize(Throwable $exception): array;

    /**
     * supports
     * Checks if the exception is supported by the normalizer.
     *
     * @param Throwable $exception
     *
     * @return bool
     */
    public function supports(Throwable $exception): bool;
}
