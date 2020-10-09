<?php

namespace App\Normalizer;

use Throwable;

interface AppNormalizerInterface
{    
    /**
     * normalize
     *
     * @param Throwable $exception
     * @return array<string, mixed>
     */
    public function normalize(Throwable $exception): array;
    
    /**
     * supports
     * Checks if the exception is supported by the normalizer
     *
     * @param Throwable $exception
     * @return bool
     */
    public function supports(Throwable $exception): bool;
}
