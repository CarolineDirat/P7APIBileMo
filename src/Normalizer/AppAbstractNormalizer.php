<?php

namespace App\Normalizer;

use Throwable;

/**
 * AppAbstractNormalizer
 * 
 * @package App\Normalizer
 */
abstract class AppAbstractNormalizer implements AppNormalizerInterface
{    
    /**
     * exceptionTypes
     * Types of exceptions supported by the normalizer.
     *
     * @var string[]
     */
    protected array $exceptionTypes;
    
    /**
     * __construct
     *
     * @param  string[] $exceptionTypes
     * @return void
     */
    public function __construct(array $exceptionTypes)
    {
        $this->exceptionTypes = $exceptionTypes;
    }

    public function supports(Throwable $exception): bool
    {
        return in_array(get_class($exception), $this->exceptionTypes);
    }
}
